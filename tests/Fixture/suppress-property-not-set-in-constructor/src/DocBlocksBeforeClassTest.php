<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class DocBlocksBeforeClassTest extends TestCase
{
    private \stdClass $propertyName;

    public function testObject(): void
    {
        self::assertInstanceOf(
            \stdClass::class,
            $this->propertyName
        );
    }

    /** @beforeClass */
    private function methodName(): void
    {
        $this->propertyName = new \stdClass();
    }
}
