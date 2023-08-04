<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractHook;

use Psalm\Issue\InternalClass;
use Psalm\Plugin\EventHandler\BeforeAddIssueInterface;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressInternalClassHook extends AbstractHook implements BeforeAddIssueInterface
{
    /**
     * @return null|false
     */
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        if (! $codeIssue instanceof InternalClass) {
            return self::IGNORE;
        }

        if (str_contains($codeIssue->fq_classlike_name, __NAMESPACE__)) {
            return self::SUPPRESS;
        }

        return self::IGNORE;
    }
}
