<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

abstract class AbstractHook
{
    final public const CONTINUE = null;

    final public const KEEP = true;

    final public const SUPPRESS = false;
}
