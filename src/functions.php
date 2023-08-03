<?php

declare(strict_types=1);

if (! function_exists('blm')) {
    function blm(): string
    {
        return BLACK_LIVES_MATTER;
    }
}

if (! function_exists('blackLivesMatter')) {
    function blackLivesMatter(): string
    {
        return BLACK_LIVES_MATTER;
    }
}

if (! function_exists('dd')) {
    /**
     * @psalm-suppress ForbiddenCode
     */
    function dd(): void
    {
        var_dump(...func_get_args());

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        if (array_key_exists('file', $trace[1]) && array_key_exists('line', $trace[1])) {
            echo sprintf(PHP_EOL . '// dd() called from: %s:%s' . PHP_EOL, $trace[1]['file'], $trace[1]['line']), PHP_EOL;
        }

        die(42);
    }
}
