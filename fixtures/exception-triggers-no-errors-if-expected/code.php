<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class MyTest extends TestCase
{
    /** @return void */
    public function testSomething()
    {
        $this->expectException(\InvalidArgumentException::class);
        throw new \InvalidArgumentException('foo');
    }
}
