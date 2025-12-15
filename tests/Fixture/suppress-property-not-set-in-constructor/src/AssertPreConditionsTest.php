<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class AssertPreConditionsTest extends TestCase
{
    private \stdClass $propertyName;

    protected function assertPreConditions(): void
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
