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
use Psalm\Codebase;
use Psalm\Internal\Scanner\ParsedDocblock;
use Psalm\Issue\PropertyNotSetInConstructor;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;
use Psalm\Storage\MethodStorage;

final class SuppressPropertyNotSetInConstructorHook extends AbstractBeforeAddIssueEventHook
{
    private static ?NodeFinder $nodeFinder = null;

    /**
     * @return null|false
     */
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        if (! $codeIssue instanceof PropertyNotSetInConstructor) {
            return self::IGNORE;
        }

        if (! self::isPropertySetInConstructor($codeIssue, $event->getCodebase())) {
            return self::IGNORE;
        }

        return self::SUPPRESS;
    }

    public static function getNodeFinder(): NodeFinder
    {
        return self::$nodeFinder ??= new NodeFinder();
    }

    /**
     * @param array<Node>|Node $classMethodNodes
     */
    private static function hasClassMethodWithDocBlockTagAndPropertyAssignment(string $tagName, string $propertyName, Node|array $classMethodNodes): bool
    {
        return self::getNodeFinder()->findFirst(
            $classMethodNodes,
            static function (Node $node) use ($tagName, $propertyName): bool {
                if (! $node instanceof ClassMethod) {
                    return false;
                }

                $parsedDocBlock = self::parseDocComment($node);
                if (! $parsedDocBlock instanceof ParsedDocblock) {
                    return false;
                }

                if (! array_key_exists($tagName, $parsedDocBlock->tags)) {
                    return false;
                }

                return self::hasPropertyAssignment($propertyName, $node);
            }
        ) instanceof ClassMethod;
    }

    /**
     * @param array<Node>|Node $classMethodNodes
     */
    private static function hasClassMethodWithPHPAttributeAndPropertyAssignment(
        string $attributeName,
        string $propertyName,
        Node|array $classMethodNodes
    ): bool {
        return self::getNodeFinder()->findFirst(
            $classMethodNodes,
            static function (Node $node) use ($attributeName, $propertyName): bool {
                if (! $node instanceof ClassMethod) {
                    return false;
                }

                foreach ($node->attrGroups as $attrGroup) {
                    foreach ($attrGroup->attrs as $attr) {
                        if (! $attr instanceof Attribute) {
                            continue;
                        }

                        if ($attr->name->toString() === $attributeName) {
                            return self::hasPropertyAssignment($propertyName, $node);
                        }
                    }
                }

                return false;
            }
        ) instanceof ClassMethod;
    }

    /**
     * @param array<Node>|Node $classMethodNodes
     */
    private static function hasClassMethodWithPropertyAssignment(string $methodName, string $propertyName, Node|array $classMethodNodes): bool
    {
        return self::getNodeFinder()->findFirst(
            $classMethodNodes,
            static function (Node $node) use ($methodName, $propertyName): bool {
                if (! $node instanceof ClassMethod) {
                    return false;
                }

                if ($node->name->toString() !== $methodName) {
                    return false;
                }

                return self::hasPropertyAssignment($propertyName, $node);
            }
        ) instanceof ClassMethod;
    }

    private static function hasPropertyAssignment(string $propertyName, ClassMethod $classMethodNode): bool
    {
        return self::getNodeFinder()->findFirst(
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

                return $name->toString() === $propertyName;
            }
        ) instanceof Expression;
    }

    private static function isPropertySetInConstructor(
        PropertyNotSetInConstructor $propertyNotSetInConstructor,
        Codebase $codebase
    ): bool {
        $propertyId = $propertyNotSetInConstructor->property_id;

        [$className, $propertyName] = explode('::$', $propertyId);

        if (! $codebase->classExtends($className, TestCase::class)) {
            return false;
        }

        $classStorage = $codebase->classlike_storage_provider->get($className);

        $methodStoragesWithPropertyMutations = array_filter(
            $classStorage->methods,
            static fn (
                MethodStorage $methodStorage
            ): bool => array_key_exists($propertyName, $methodStorage->this_property_mutations ?? [])
        );

        if ($methodStoragesWithPropertyMutations === []) {
            return false;
        }

        /** @var array<Node> $statements */
        $statements = $codebase->getStatementsForFile($propertyNotSetInConstructor->getFilePath());

        /** @var list<ClassMethod> $classMethodNodes */
        $classMethodNodes = self::getNodeFinder()->findInstanceOf($statements, ClassMethod::class);

        $protectedStaticSetupMethodNames = ['setUp', 'setupBeforeClass'];
        foreach ($protectedStaticSetupMethodNames as $methodName) {
            if (self::hasClassMethodWithPropertyAssignment($methodName, $propertyName, $classMethodNodes)) {
                return true;
            }
        }

        $beforeDocBlockTagNames = ['before', 'beforeClass'];
        foreach ($beforeDocBlockTagNames as $tagName) {
            if (self::hasClassMethodWithDocBlockTagAndPropertyAssignment($tagName, $propertyName, $classMethodNodes)) {
                return true;
            }
        }

        $beforePHPAttributeNames = ['Before', 'BeforeClass'];
        foreach ($beforePHPAttributeNames as $attributeName) {
            if (self::hasClassMethodWithPHPAttributeAndPropertyAssignment($attributeName, $propertyName, $classMethodNodes)) {
                return true;
            }
        }

        return false;
    }
}
