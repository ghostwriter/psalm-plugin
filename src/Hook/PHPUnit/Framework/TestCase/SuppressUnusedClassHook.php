<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook\PHPUnit\Framework\TestCase;

use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;
use PHPUnit\Framework\TestCase;
use Psalm\Issue\UnusedClass;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressUnusedClassHook extends AbstractBeforeAddIssueEventHook
{
    /**
     * @return false|null
     */
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();
        if (!$codeIssue instanceof UnusedClass) {
            return self::IGNORE;
        }

        $className = $codeIssue->fq_classlike_name;

        if (str_contains($className, __NAMESPACE__)) {
            // Hooks are autoloaded
            return self::SUPPRESS;
        }

        $codebase = $event->getCodebase();

        if ($codebase->classExtends($className, TestCase::class)) {
            return self::SUPPRESS;
        }

        return self::IGNORE;
    }
}
