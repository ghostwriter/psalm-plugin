<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;
use Psalm\Issue\InternalMethod;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressInternalMethodHook extends AbstractBeforeAddIssueEventHook
{
    /**
     * @return false|null
     */
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        if (!$codeIssue instanceof InternalMethod) {
            return self::IGNORE;
        }

        if (str_contains($codeIssue->message, 'Ghostwriter\\PsalmPlugin')) {
            return self::SUPPRESS;
        }

        return self::IGNORE;
    }
}
