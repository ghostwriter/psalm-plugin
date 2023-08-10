<?php declare(strict_types=1);
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\TestCase;

final class MyTest extends TestCase
{
    private int $sut;

    #[Before]
    public function setupFixture(): void
    {
        $this->sut = 42;
    }

    public function testFixture(): void
    {
        Assert::assertSame(42, $this->sut);
    }
}
