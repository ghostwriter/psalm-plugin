<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

abstract class AbstractHook
{
    final public const IGNORE = null;

    final public const REPORT = true;

    final public const SUPPRESS = false;
}
