<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook\Ghostwriter\PsalmPlug;

use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;
use Psalm\Issue\InternalClass;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

use function str_contains;

final class SuppressInternalClassHook extends AbstractBeforeAddIssueEventHook
{
    /** @return null|false */
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        if (! $codeIssue instanceof InternalClass) {
            return self::IGNORE;
        }

        if (str_contains($codeIssue->message, 'Ghostwriter\\PsalmPlugin')) {
            return self::SUPPRESS;
        }

        return self::IGNORE;
    }
}
