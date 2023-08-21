<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use PHPUnit\Framework\Attributes\BeforeClass;
use PHPUnit\Framework\TestCase;

final class AttributesBeforeClassTest extends TestCase
{
    private \stdClass $propertyName;

    public function testObject(): void
    {
        self::assertInstanceOf(
            \stdClass::class,
            $this->propertyName
        );
    }

    #[BeforeClass]
    private function methodName(): void
    {
        $this->propertyName = new \stdClass();
    }
}
