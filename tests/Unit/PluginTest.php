<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use Generator;
use Ghostwriter\PsalmPlugin\AbstractHook;
use Ghostwriter\PsalmPlugin\Hook\FixParamNameMismatchHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressInternalClassHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressInternalMethodHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressInternalPropertyHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressMissingConstructorHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressMissingThrowsDocblockHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressPropertyNotSetInConstructorHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressUnusedClassHook;
use Ghostwriter\PsalmPlugin\Plugin;
use Ghostwriter\PsalmPluginTester\Fixture;
use Ghostwriter\PsalmPluginTester\PluginTester;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Plugin::class)]
#[CoversClass(AbstractHook::class)]
#[CoversClass(FixParamNameMismatchHook::class)]
#[CoversClass(SuppressMissingConstructorHook::class)]
#[CoversClass(SuppressMissingThrowsDocblockHook::class)]
#[CoversClass(SuppressUnusedClassHook::class)]
#[CoversClass(SuppressPropertyNotSetInConstructorHook::class)]
#[CoversClass(SuppressInternalClassHook::class)]
#[CoversClass(SuppressInternalMethodHook::class)]
#[CoversClass(SuppressInternalPropertyHook::class)]
final class PluginTest extends TestCase
{
    private PluginTester $pluginTester;

    protected function setUp(): void
    {
        $this->pluginTester = new PluginTester();
    }

    /** @return Generator<string,Fixture> */
    public static function fixtureDataProvider(): Generator
    {
        yield from PluginTester::yieldFixtures(
            dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'fixtures'
        );
    }

    #[DataProvider('fixtureDataProvider')]
    public function testPlugin(Fixture $fixture): void
    {
        $this->pluginTester->testPlugin(
            Plugin::class,
            $fixture
        );
    }
}
