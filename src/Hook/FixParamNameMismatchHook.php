<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractHook;


use Psalm\Plugin\EventHandler\BeforeAddIssueInterface;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class FixParamNameMismatchHook extends AbstractHook implements BeforeAddIssueInterface
{
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        return self::IGNORE;
    }
}
