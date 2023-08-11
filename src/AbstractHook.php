<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use Psalm\Codebase;
use Psalm\DocComment;
use Psalm\Internal\Scanner\ParsedDocblock;

abstract class AbstractHook
{
    final public const IGNORE = null;

    final public const REPORT = true;

    final public const SUPPRESS = false;

    public static function dump(): void
    {
        array_map(static fn ($arg) => is_object($arg) ? var_export($arg) : var_dump($arg), func_get_args());
        die(42);
    }

    public static function fullyQualifiedClassName(string $className, Codebase $codebase): string
    {
        return $codebase->classlikes->getUnAliasedName($className);
    }

    public static function hasFullyQualifiedClassName(string $className, Codebase $codebase): bool
    {
        return $codebase->classlikes->hasFullyQualifiedClassName($className);
    }

    public static function isClassReferenced(string $className, Codebase $codebase): bool
    {
        $fullyQualifiedClassName = self::fullyQualifiedClassName($className, $codebase);

        return $codebase->file_reference_provider->isClassReferenced(mb_strtolower($fullyQualifiedClassName));
    }

    public static function parseDocComment(Node $node): ?ParsedDocblock
    {
        $doc = $node->getDocComment();
        if (! $doc instanceof Doc) {
            return null;
        }

        return DocComment::parsePreservingLength($doc);
    }
}
