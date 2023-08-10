<?php

declare(strict_types=1);

namespace Vendor\Package;

use PHPUnit\Framework\TestCase;

trait MyTestTrait
{
    /**
     * @return void
     * @dataProvider provide
     */
    public function foo(int $_i)
    {
    }
}
