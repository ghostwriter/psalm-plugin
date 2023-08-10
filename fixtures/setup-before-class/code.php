<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase
{
    private static PDO $dbh;

    public static function setUpBeforeClass(): void
    {
        self::$dbh = new \PDO('sqlite::memory:');
    }

    public static function tearDownAfterClass(): void
    {
        self::$dbh = null;
    }
}
