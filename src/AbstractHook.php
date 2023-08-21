<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use Ghostwriter\Json\Json;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use Psalm\Codebase;
use Psalm\DocComment;
use Psalm\Internal\Scanner\ParsedDocblock;

use function is_object;
use function var_export;

abstract class AbstractHook
{
    final public const IGNORE = null;

    final public const REPORT = true;

    final public const SUPPRESS = false;

    private static ?NodeFinder $nodeFinder = null;

    public static function dump(): void
    {
        echo PHP_EOL.PHP_EOL.'DUMP'.PHP_EOL.PHP_EOL;

        /* @psalm-suppress ForbiddenCode */
        \var_dump(
            \array_map(
                static fn (mixed $arg): mixed => $arg,
                // is_object($arg) ? Json::encode(var_export($arg, true), Json::PRETTY) :
                \func_get_args()
            )
        );

        exit(42);
    }

    /**
     * @param Node|Node[]         $nodes
     * @param callable(Node):bool $filter
     */
    public static function findFirst(Node|array $nodes, callable $filter): ?Node
    {
        return self::getNodeFinder()->findFirst($nodes, $filter);
    }

    public static function fullyQualifiedClassName(string $className, Codebase $codebase): string
    {
        return $codebase->classlikes->getUnAliasedName($className);
    }

    public static function getNodeFinder(): NodeFinder
    {
        return self::$nodeFinder ??= new NodeFinder();
    }

    public static function hasFullyQualifiedClassName(string $className, Codebase $codebase): bool
    {
        return $codebase->classlikes->hasFullyQualifiedClassName($className);
    }

    /**
     * @param Node|Node[]         $nodes
     * @param callable(Node):bool $filter
     */
    public static function hasNode(Node|array $nodes, callable $filter): bool
    {
        return self::findFirst($nodes, $filter) instanceof Node;
    }

    /**
     * @param Node|Node[]         $nodes
     * @param callable(Node):bool $filter
     */
    public static function getNode(Node|array $nodes, callable $filter): Node
    {
        $node = self::findFirst($nodes, $filter);

        if (!$node instanceof Node) {
            throw new \RuntimeException('Node not found');
        }

        return $node;
    }

    /**
     * @param Node|Node[]         $nodes
     * @param callable(Node):bool $filter
     */
    public static function getClassMethodNode(Node|array $nodes, callable $filter): ClassMethod
    {
        $node = self::findFirst($nodes, $filter);

        if (!$node instanceof ClassMethod) {
            throw new \RuntimeException('ClassMethod not found');
        }

        return $node;
    }

    public static function isClassReferenced(string $className, Codebase $codebase): bool
    {
        $fullyQualifiedClassName = self::fullyQualifiedClassName($className, $codebase);

        return $codebase->file_reference_provider->isClassReferenced(\mb_strtolower($fullyQualifiedClassName));
    }

    public static function parseDocComment(Doc $doc): ?ParsedDocblock
    {
        return DocComment::parsePreservingLength($doc);
    }

    public static function parseDocCommentNode(Node $node): ?ParsedDocblock
    {
        $doc = $node->getDocComment();
        if (!$doc instanceof Doc) {
            return null;
        }

        return self::parseDocComment($doc);
    }
}
