<?php declare(strict_types=1);
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class MyTest extends TestCase
{
    private static int $sut;

    /**
     * @beforeClass
     */
    public static function setupFixture(): void
    {
        self::$sut = 42;
    }

    public static function testFixture(): void
    {
        Assert::assertSame(42, self::$sut);
    }
}
