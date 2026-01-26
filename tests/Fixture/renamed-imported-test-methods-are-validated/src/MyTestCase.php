<?php

declare(strict_types=1);

namespace Vendor\Package;

use PHPUnit\Framework\TestCase;

class MyTestCase extends TestCase
{
    use MyTestTrait {
        foo as testAnything;
    }
}
