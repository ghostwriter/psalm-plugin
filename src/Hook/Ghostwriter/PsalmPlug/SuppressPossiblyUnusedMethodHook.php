<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook\Ghostwriter\PsalmPlug;

use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;
use Psalm\Issue\PossiblyUnusedMethod;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressPossiblyUnusedMethodHook extends AbstractBeforeAddIssueEventHook
{
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        if (!$codeIssue instanceof PossiblyUnusedMethod) {
            return self::IGNORE;
        }

        if (str_contains($codeIssue->message, 'Ghostwriter\\PsalmPlugin\\')) {
            return self::SUPPRESS;
        }

        return self::IGNORE;
    }
}
