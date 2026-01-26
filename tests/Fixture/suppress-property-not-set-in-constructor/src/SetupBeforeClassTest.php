<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class SetupBeforeClassTest extends TestCase
{
    private static \stdClass $propertyName;

    public static function setUpBeforeClass(): void
    {
        self::methodName();
    }

    public function testObject(): void
    {
        self::assertInstanceOf(
            \stdClass::class,
            self::$propertyName
        );
    }

    private static function methodName(): void
    {
        self::$propertyName = new \stdClass();
    }
}
