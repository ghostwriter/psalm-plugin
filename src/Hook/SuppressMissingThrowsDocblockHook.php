<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;
use PHPUnit\Framework\TestCase;
use Psalm\Issue\MissingThrowsDocblock;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressMissingThrowsDocblockHook extends AbstractBeforeAddIssueEventHook
{
    /**
     * @return false|null
     */
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();

        if (!$codeIssue instanceof MissingThrowsDocblock) {
            return self::IGNORE;
        }

        $codeLocation = $codeIssue->code_location;

        $codebase = $event->getCodebase();

        foreach ($codebase->classlike_storage_provider->getAll() as $storage) {
            if (null === $storage->location) {
                continue;
            }

            if ($storage->location->file_path !== $codeLocation->file_path) {
                continue;
            }

            if (!$codebase->classExtends($storage->name, TestCase::class)) {
                continue;
            }

            return self::SUPPRESS;
        }

        return self::IGNORE;
    }
}
