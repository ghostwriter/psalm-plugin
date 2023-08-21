<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\BeforeClass;
use PHPUnit\Framework\TestCase;
use Psalm\Codebase;
use Psalm\Internal\Scanner\ParsedDocblock;
use Psalm\Issue\PropertyNotSetInConstructor;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;
use Psalm\Storage\MethodStorage;

use function array_key_exists;

final class SuppressPropertyNotSetInConstructorHook extends AbstractBeforeAddIssueEventHook
{
    /**
     * @return false|null
     */
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        if (!$codeIssue instanceof PropertyNotSetInConstructor) {
            return self::IGNORE;
        }

        if (!self::isPropertySetInConstructor($codeIssue, $event->getCodebase())) {
            return self::IGNORE;
        }

        return self::SUPPRESS;
    }

    /**
     * @param array<ClassMethod> $classMethodNodes
     */
    private static function hasClassMethodStatementWithDocBlockTagAndPropertyAssignmentExpression(
        string $tagName,
        string $propertyName,
        Node|array $classMethodNodes
    ): bool {
        return self::hasNode(
            $classMethodNodes,
            static function (Node $node) use ($tagName, $propertyName): bool {
                if (!$node instanceof ClassMethod) {
                    return false;
                }

                $parsedDocBlock = self::parseDocCommentNode($node);
                if (!$parsedDocBlock instanceof ParsedDocblock) {
                    return false;
                }

                if (!\array_key_exists($tagName, $parsedDocBlock->tags)) {
                    return false;
                }

                return self::hasPropertyAssignmentExpression($propertyName, $node);
            }
        );
    }

    /**
     * @param array<ClassMethod> $classMethodNodes
     */
    private static function hasClassMethodStatementWithPHPAttributeAndPropertyAssignmentExpression(
        string $attributeName,
        string $propertyName,
        array $classMethodNodes
    ): bool {
        return self::hasNode(
            $classMethodNodes,
            static function (Node $node) use ($attributeName, $propertyName): bool {
                if (!$node instanceof ClassMethod) {
                    return false;
                }

                foreach ($node->attrGroups as $attributeGroup) {
                    foreach ($attributeGroup->attrs as $attribute) {
                        if (!$attribute instanceof Attribute) {
                            continue;
                        }

                        if ($attribute->name->toString() !== $attributeName) {
                            continue;
                        }

                        return self::hasPropertyAssignmentExpression($propertyName, $node);
                    }
                }

                return false;
            }
        );
    }

    /**
     * @param array<ClassMethod> $classMethodNodes
     */
    private static function hasClassMethodStatementWithPropertyAssignmentExpression(
        string $methodName,
        string $propertyName,
        array $classMethodNodes
    ): bool {
        return self::hasNode(
            $classMethodNodes,
            static function (Node $node) use ($methodName, $propertyName, $classMethodNodes): bool {
                if (!$node instanceof ClassMethod) {
                    return false;
                }

                if ($node->name->toString() !== $methodName) {
                    return false;
                }

                return
                    self::hasPropertyAssignmentExpression($propertyName, $node)
                    || self::hasMethodCallExpressionWithPropertyAssignmentExpression($propertyName, $node, $classMethodNodes);
            }
        );
    }

    /**
     * @param array<ClassMethod> $classMethodNodes
     */
    private static function hasMethodCallExpressionWithPropertyAssignmentExpression(
        string $propertyName,
        ClassMethod $classMethodNode,
        array $classMethodNodes
    ): bool {
        return self::hasNode(
            $classMethodNode,
            static function (Node $node) use ($classMethodNodes, $propertyName): bool {
                if (!$node instanceof Expression) {
                    return false;
                }

                $expr = $node->expr;

                if (!$expr instanceof MethodCall) {
                    return false;
                }

                if (
                    self::hasPropertyAssignmentExpression(
                        $propertyName,
                        self::getClassMethodNode($classMethodNodes, static function (Node $node) use ($expr): bool {
                            if (!$node instanceof ClassMethod) {
                                return false;
                            }

                            /** @var Identifier $name */
                            $name = $expr->name;

                            if ($node->name->toString() !== $name->toString()) {
                                return false;
                            }

                            return true;
                        })
                    )
                ) {
                    return true;
                }

                return false;
            }
        );
    }

    private static function hasPropertyAssignmentExpression(
        string $propertyName,
        ClassMethod $classMethodNode
    ): bool {
        return self::hasNode(
            $classMethodNode,
            static function (Node $node) use ($propertyName): bool {
                if (!$node instanceof Expression) {
                    return false;
                }

                $expr = $node->expr;
                if (!$expr instanceof Assign) {
                    return false;
                }

                $var = $expr->var;
                if (!$var instanceof PropertyFetch) {
                    return false;
                }

                $name = $var->name;
                if (!$name instanceof Identifier) {
                    return false;
                }

                return $name->toString() === $propertyName;
            }
        );
    }

    private static function isPropertySetInConstructor(
        PropertyNotSetInConstructor $propertyNotSetInConstructor,
        Codebase $codebase
    ): bool {
        $propertyId = $propertyNotSetInConstructor->property_id;

        [$className, $propertyName] = \explode('::$', $propertyId);

        if (!$codebase->classExtends($className, TestCase::class)) {
            return false;
        }

        $classStorage = $codebase->classlike_storage_provider->get($className);

        /** @var list<ClassMethod> $classMethodNodes */
        $classMethodNodes = self::getNodeFinder()->findInstanceOf(
            $codebase->getStatementsForFile(
                $propertyNotSetInConstructor->getFilePath()
            ),
            ClassMethod::class
        );

        $protectedSetupMethodNames = ['setUp', 'setupBeforeClass', 'assertPreConditions'];
        foreach ($protectedSetupMethodNames as $methodName) {
            if (self::hasClassMethodStatementWithPropertyAssignmentExpression($methodName, $propertyName, $classMethodNodes)) {
                return true;
            }
        }

        $beforeDocBlockTagNames = ['before', 'beforeClass'];
        foreach ($beforeDocBlockTagNames as $tagName) {
            if (self::hasClassMethodStatementWithDocBlockTagAndPropertyAssignmentExpression($tagName, $propertyName, $classMethodNodes)) {
                return true;
            }
        }

        $beforeAttributeNames = [Before::class, BeforeClass::class];
        foreach ($beforeAttributeNames as $attributeName) {
            if (self::hasClassMethodStatementWithPHPAttributeAndPropertyAssignmentExpression($attributeName, $propertyName, $classMethodNodes)) {
                return true;
            }
        }

        foreach ($classStorage->methods as $methodStorage) {
            $thisPropertyMutations = $methodStorage->this_property_mutations;
            if (null === $thisPropertyMutations) {
                continue;
            }

            if (!\array_key_exists($propertyName, $thisPropertyMutations)) {
                continue;
            }

            $methodName = $methodStorage->cased_name;
            if (null === $methodName) {
                continue;
            }

            // Attributes
            foreach ($methodStorage->getAttributeStorages() as $attributeStorage) {
                $attributeName = $attributeStorage->fq_class_name;

                if (!in_array($attributeName, $beforeAttributeNames, true)) {
                    continue;
                }

                return true;
            }

            // DocBlock
            $codeLocation = $methodStorage->stmt_location;
            if (null === $codeLocation) {
                continue;
            }

            $docs = self::parseDocComment(
                new Doc(
                    mb_substr(
                        $codebase->getFileContents($codeLocation->file_path),
                        $codeLocation->docblock_start ?? -1,
                        $codeLocation->raw_file_start
                    ),
                    $codeLocation->raw_line_number,
                    $codeLocation->raw_file_start,
                )
            );

            if (null === $docs) {
                continue;
            }

            if ([] === $docs->tags) {
                continue;
            }

            // $tags = array_keys($docs->tags);

            foreach ($beforeDocBlockTagNames as $tagName) {
                if (self::hasClassMethodStatementWithDocBlockTagAndPropertyAssignmentExpression($tagName, $propertyName, $classMethodNodes)) {
                    return true;
                }
            }


            if (self::hasClassMethodStatementWithPropertyAssignmentExpression($methodName, $propertyName, $classMethodNodes)) {
                return true;
            }
        }

        return false;
    }
}
