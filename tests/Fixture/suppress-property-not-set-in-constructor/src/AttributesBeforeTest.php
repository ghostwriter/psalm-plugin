<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\TestCase;

final class AttributesBeforeTest extends TestCase
{
    private \stdClass $propertyName;

    public function testObject(): void
    {
        self::assertInstanceOf(
            \stdClass::class,
            $this->propertyName
        );
    }

    #[Before]
    private function methodName(): void
    {
        $this->propertyName = new \stdClass();
    }
}
