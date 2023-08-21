<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;
use Psalm\Issue\InternalProperty;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressInternalPropertyHook extends AbstractBeforeAddIssueEventHook
{
    /**
     * @return false|null
     */
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        if (!$codeIssue instanceof InternalProperty) {
            return self::IGNORE;
        }

        if (str_contains($codeIssue->message, 'Ghostwriter\\PsalmPlugin')) {
            return self::SUPPRESS;
        }

        return self::IGNORE;
    }
}
