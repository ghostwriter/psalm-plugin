<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractHook;

use PHPUnit\Exception;
use Psalm\Issue\MissingThrowsDocblock;
use Psalm\Plugin\EventHandler\BeforeAddIssueInterface;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressMissingThrowsDocblockHook extends AbstractHook implements BeforeAddIssueInterface
{
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        if (! $codeIssue instanceof MissingThrowsDocblock) {
            return self::IGNORE;
        }

        $className = $codeIssue->fq_classlike_name;

        if ($event->getCodebase()->classImplements($codeIssue->fq_classlike_name, Exception::class)) {
            return self::SUPPRESS;
        }

        if ($className === Exception::class) {
            return self::SUPPRESS;
        }

        return self::IGNORE;
    }
}
