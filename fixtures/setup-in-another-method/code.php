<?php

declare(strict_types=1);

namespace Vendor\Package;

use PHPUnit\Framework\TestCase;

final class ObjectTest extends TestCase
{
    private \stdClass $subject;

    protected function setUp(): void
    {
        $this->configure();
    }

    private function configure(): void
    {
        $this->subject = new \stdClass();
    }

    public function testObject(): void
    {
        self::assertInstanceOf(
            \stdClass::class,
            $this->subject
        );
    }
}
