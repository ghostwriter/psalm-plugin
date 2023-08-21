<?php

declare(strict_types=1);

namespace Vendor\Package;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MyTestCaseSetup extends TestCase
{
    /** @var MockObject&I */
    private $i;

    public function setUp(): void
    {
        $this->i = $this->createMock(I::class);
    }

    /** @return void */
    public function testSomething()
    {
        $i = $this->i->work()->willReturn(1);
        $this->assertEquals(1, $i->work());
    }
}
