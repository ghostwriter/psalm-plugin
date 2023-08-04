<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook;

use Ghostwriter\PsalmPlugin\AbstractHook;

use PHPUnit\Framework\TestCase;
use Psalm\Issue\PropertyNotSetInConstructor;
use Psalm\Plugin\EventHandler\BeforeAddIssueInterface;
use Psalm\Plugin\EventHandler\Event\BeforeAddIssueEvent;

final class SuppressPropertyNotSetInConstructorHook extends AbstractHook implements BeforeAddIssueInterface
{
    public static function beforeAddIssue(BeforeAddIssueEvent $event): ?bool
    {
        $codeIssue = $event->getIssue();
        if (! $codeIssue instanceof PropertyNotSetInConstructor) {
            return self::IGNORE;
        }

        $propertyId = $codeIssue->property_id;

        [$className, $propertyName] = explode('::$', $propertyId);

        $codebase = $event->getCodebase();

        if (! $codebase->classExtends($className, TestCase::class)) {
            return self::IGNORE;
        }

        $classLikeStorage = $codebase->classlikes->getStorageFor($className);
        if (array_key_exists($propertyName, $classLikeStorage->methods['setup']->this_property_mutations ?? [])) {
            return self::SUPPRESS;
        }

        return self::IGNORE;
    }
}
