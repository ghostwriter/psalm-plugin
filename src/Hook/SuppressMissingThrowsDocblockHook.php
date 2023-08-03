<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractHook;

use PHPUnit\Framework\Exception;
use Psalm\Issue\MissingThrowsDocblock;
use Psalm\Plugin\EventHandler\BeforeAddIssueInterface;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressMissingThrowsDocblockHook extends AbstractHook implements BeforeAddIssueInterface
{
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();
        if (! $codeIssue instanceof MissingThrowsDocblock) {
            return self::CONTINUE;
        }

        if (! $event->getCodebase()->classExtends($codeIssue->fq_classlike_name, Exception::class)) {
            return self::CONTINUE;
        }

        return self::SUPPRESS;
    }
}
