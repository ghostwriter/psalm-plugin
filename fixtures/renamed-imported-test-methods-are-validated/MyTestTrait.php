<?php

declare(strict_types=1);

namespace Vendor\Package;

trait MyTestTrait
{
    /**
     * @return void
     *
     * @dataProvider provide
     */
    public function foo(int $_i)
    {
    }
}
