<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractHook;

use Psalm\Issue\InternalMethod;
use Psalm\Plugin\EventHandler\BeforeAddIssueInterface;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressInternalMethodHook extends AbstractHook implements BeforeAddIssueInterface
{
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        return match (true) {
            default => self::CONTINUE ,
            $codeIssue instanceof InternalMethod => match (true) {
                str_contains($codeIssue->message, __NAMESPACE__) => self::SUPPRESS,
                default => self::CONTINUE ,
            }
        };
    }
}
