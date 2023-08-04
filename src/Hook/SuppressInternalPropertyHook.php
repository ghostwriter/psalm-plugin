<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractHook;

use Psalm\Issue\InternalProperty;
use Psalm\Plugin\EventHandler\BeforeAddIssueInterface;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressInternalPropertyHook extends AbstractHook implements BeforeAddIssueInterface
{
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        if (! $codeIssue instanceof InternalProperty) {
            return self::IGNORE;
        }

        if (str_contains($codeIssue->message, __NAMESPACE__)) {
            return self::SUPPRESS;
        }

        return self::IGNORE;
    }
}
