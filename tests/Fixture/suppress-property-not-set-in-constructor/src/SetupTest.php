<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use PHPUnit\Framework\TestCase;

/** @coversNothing */
final class SetupTest extends TestCase
{
    private \stdClass $propertyName;

    protected function setUp(): void
    {
        $this->methodName();
    }

    public function testObject(): void
    {
        self::assertInstanceOf(
            \stdClass::class,
            $this->propertyName
        );
    }

    private function methodName(): void
    {
        $this->propertyName = new \stdClass();
    }
}
