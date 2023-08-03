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
            return self::CONTINUE;
        }

        return match (true) {
            default => self::CONTINUE,
            str_contains($codeIssue->fq_classlike_name, __NAMESPACE__),
            $event->getCodebase()->classExtends($codeIssue->fq_classlike_name, TestCase::class) => self::SUPPRESS
        };
    }
}
