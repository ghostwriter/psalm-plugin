<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractHook;

use PHPUnit\Framework\TestCase;
use Psalm\Issue\UnusedClass;
use Psalm\Plugin\EventHandler\BeforeAddIssueInterface;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressUnusedClassHook extends AbstractHook implements BeforeAddIssueInterface
{
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();
        if (! $codeIssue instanceof UnusedClass) {
            return self::IGNORE;
        }

        if (str_starts_with($codeIssue->fq_classlike_name, __NAMESPACE__))
        {
            return self::SUPPRESS;
        }

        if ($event->getCodebase()->classExtends($codeIssue->fq_classlike_name, TestCase::class))
        {
            return self::SUPPRESS;
        }

        return self::IGNORE;
    }
}
