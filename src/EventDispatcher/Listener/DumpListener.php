<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\EventDispatcher\Listener;

use function var_dump;

final readonly class DumpListener
{
    /**
     * @param object $event
     *
     * @psalm-suppress ForbiddenCode
     *
     */
    public function __invoke(object $event): void
    {
        var_dump($event);
    }
}
