<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use Psalm\Plugin\EventHandler\BeforeAddIssueInterface;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

abstract class AbstractBeforeAddIssueEventHook extends AbstractHook implements BeforeAddIssueInterface
{
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        return self::IGNORE;
    }
}
