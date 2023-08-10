<?php

declare(strict_types=1);

namespace Vendor\Package;

use PHPUnit\Framework\TestCase;

class MyTestCase extends TestCase
{
    /**
     * @param mixed $int
     * @return void
     * @psalm-suppress UnusedMethod
     * @dataProvider provide
     */
    public function testSomething($int)
    {
        $this->assertEquals(1, $int);
    }
}
