<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class DocBlocksBeforeTest extends TestCase
{
    private \stdClass $propertyName;

    public function testObject(): void
    {
        self::assertInstanceOf(
            \stdClass::class,
            $this->propertyName
        );
    }

    /** @before */
    private function methodName(): void
    {
        $this->propertyName = new \stdClass();
    }
}
