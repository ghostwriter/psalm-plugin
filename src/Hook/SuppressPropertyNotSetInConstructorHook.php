<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeFinder;
use PHPUnit\Framework\TestCase;
use Psalm\Internal\Scanner\ParsedDocblock;
use Psalm\Issue\PropertyNotSetInConstructor;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressPropertyNotSetInConstructorHook extends AbstractBeforeAddIssueEventHook
{
    /**
     * @return null|false
     */
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        if (! $codeIssue instanceof PropertyNotSetInConstructor) {
            return self::IGNORE;
        }

        $propertyId = $codeIssue->property_id;

        [$className, $propertyName] = explode('::$', $propertyId);

        $codebase = $event->getCodebase();

        if (! $codebase->classExtends($className, TestCase::class)) {
            return self::IGNORE;
        }

        /** @var array<Node> $statements */
        $statements = $codebase->getStatementsForFile($codeIssue->getFilePath());

        $nodeFinder = new NodeFinder();

        /** @var list<ClassMethod> $classMethodNodes */
        $classMethodNodes = $nodeFinder->findInstanceOf($statements, ClassMethod::class);
        if ($classMethodNodes === []) {
            return self::IGNORE;
        }

        return match (true) {
            self::hasClassMethodPropertyAssignment('setUp', $propertyName, $nodeFinder, $classMethodNodes),
            self::hasClassMethodPropertyAssignment('setupBeforeClass', $propertyName, $nodeFinder, $classMethodNodes),
            self::hasClassMethodDocBlockTagPropertyAssignment('before', $propertyName, $nodeFinder, $classMethodNodes),
            self::hasClassMethodDocBlockTagPropertyAssignment('beforeClass', $propertyName, $nodeFinder, $classMethodNodes),
            self::hasClassMethodPHPAttributePropertyAssignment('Before', $propertyName, $nodeFinder, $classMethodNodes),
            self::hasClassMethodPHPAttributePropertyAssignment('BeforeClass', $propertyName, $nodeFinder, $classMethodNodes) => self::SUPPRESS,
            default => self::IGNORE,
        };
    }

    /**
     * @param array<Node>|Node $classMethodNodes
     */
    private static function findFirstMethod(string $methodName, NodeFinder $nodeFinder, Node|array $classMethodNodes): ?Node
    {
        return $nodeFinder->findFirst(
            $classMethodNodes,
            static fn (Node $node): bool => $node instanceof ClassMethod && $node->name->toLowerString() === mb_strtolower($methodName)
        );
    }

    /**
     * @param array<Node>|Node $classMethodNodes
     */
    private static function hasClassMethodDocBlockTag(string $tagName, NodeFinder $nodeFinder, Node|array $classMethodNodes): bool
    {
        return $nodeFinder->findFirst(
            $classMethodNodes,
            static function (Node $node) use ($tagName): bool {
                if (! $node instanceof ClassMethod) {
                    return false;
                }

                $parsedDocBlock = self::parseDocComment($node);

                if (! $parsedDocBlock instanceof ParsedDocblock) {
                    return false;
                }

                return array_key_exists($tagName, $parsedDocBlock->tags);
            }
        ) instanceof Node;
    }

    /**
     * @param array<Node>|Node $classMethodNodes
     */
    private static function hasClassMethodDocBlockTagPropertyAssignment(string $tagName, string $propertyName, NodeFinder $nodeFinder, Node|array $classMethodNodes): bool
    {
        $classMethodNode = $nodeFinder->findFirst(
            $classMethodNodes,
            static function (Node $node) use ($tagName): bool {
                if (! $node instanceof ClassMethod) {
                    return false;
                }

                $parsedDocBlock = self::parseDocComment($node);

                if (! $parsedDocBlock instanceof ParsedDocblock) {
                    return false;
                }

                return array_key_exists($tagName, $parsedDocBlock->tags);
            }
        );

        if (! $classMethodNode instanceof ClassMethod) {
            return false;
        }

        return self::hasClassMethodPropertyAssignment($classMethodNode->name->__toString(), $propertyName, $nodeFinder, $classMethodNode);
    }

    /**
     * @param array<Node>|Node $classMethodNodes
     */
    private static function hasClassMethodPHPAttributePropertyAssignment(string $attributeName, string $propertyName, NodeFinder $nodeFinder, Node|array $classMethodNodes): bool
    {
        $classMethodNode = $nodeFinder->findFirst(
            $classMethodNodes,
            static function (Node $node) use ($attributeName): bool {
                if (! $node instanceof ClassMethod) {
                    return false;
                }

                foreach ($node->attrGroups as $attrGroup) {
                    foreach ($attrGroup->attrs as $attr) {
                        if (! $attr instanceof Attribute) {
                            continue;
                        }

                        if ($attr->name->toLowerString() === mb_strtolower($attributeName)) {
                            return true;
                        }
                    }
                }

                return false;
            }
        );

        if (! $classMethodNode instanceof ClassMethod) {
            return false;
        }

        return self::hasClassMethodPropertyAssignment($classMethodNode->name->__toString(), $propertyName, $nodeFinder, $classMethodNode);
    }

    /**
     * @param array<Node>|Node $classMethodNodes
     */
    private static function hasClassMethodPropertyAssignment(string $methodName, string $propertyName, NodeFinder $nodeFinder, Node|array $classMethodNodes): bool
    {
        /** @var null|ClassMethod $classMethodNode */
        $classMethodNode = self::findFirstMethod($methodName, $nodeFinder, $classMethodNodes);

        if (! $classMethodNode instanceof ClassMethod) {
            return false;
        }

        return self::hasPropertyAssignment($propertyName, $nodeFinder, $classMethodNode);
    }

    private static function hasPropertyAssignment(string $propertyName, NodeFinder $nodeFinder, ClassMethod $classMethodNode): bool
    {
        return $nodeFinder->findFirst(
            $classMethodNode,
            static function (Node $node) use ($propertyName): bool {
                if (! $node instanceof Expression) {
                    return false;
                }

                $expr = $node->expr;
                if (! $expr instanceof Assign) {
                    return false;
                }

                $var = $expr->var;
                if (! $var instanceof PropertyFetch) {
                    return false;
                }

                $name = $var->name;
                if (! $name instanceof Identifier) {
                    return false;
                }

                return mb_strtolower($name->name) === mb_strtolower($propertyName);
            }
        ) instanceof Node;
    }
}
