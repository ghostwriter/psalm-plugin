<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use CallbackFilterIterator;
use FilesystemIterator;
use Generator;
use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;
use Ghostwriter\PsalmPlugin\AbstractHook;
use Ghostwriter\PsalmPlugin\Hook\FixParamNameMismatchHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressInternalClassHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressInternalMethodHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressInternalPropertyHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressMissingConstructorHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressMissingThrowsDocblockHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressPossiblyUnusedMethodHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressPropertyNotSetInConstructorHook;
use Ghostwriter\PsalmPlugin\Hook\SuppressUnusedClassHook;
use Ghostwriter\PsalmPlugin\Plugin;
use Ghostwriter\PsalmPluginTester\Fixture;
use Ghostwriter\PsalmPluginTester\PluginTester;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

use function dirname;
use function realpath;

#[CoversClass(Plugin::class)]
#[CoversClass(AbstractBeforeAddIssueEventHook::class)]
#[CoversClass(AbstractHook::class)]
#[CoversClass(FixParamNameMismatchHook::class)]
#[CoversClass(SuppressMissingConstructorHook::class)]
#[CoversClass(SuppressMissingThrowsDocblockHook::class)]
#[CoversClass(SuppressUnusedClassHook::class)]
#[CoversClass(SuppressPropertyNotSetInConstructorHook::class)]
#[CoversClass(SuppressInternalClassHook::class)]
#[CoversClass(SuppressInternalMethodHook::class)]
#[CoversClass(SuppressPossiblyUnusedMethodHook::class)]
#[CoversClass(SuppressInternalPropertyHook::class)]
final class PluginTest extends TestCase
{
    private PluginTester $pluginTester;

    protected function setUp(): void
    {
        $this->pluginTester = new PluginTester();
    }

    /**
     * @return Generator<string,array<Fixture>>
     */
    public static function fixtureDataProvider(): Generator
    {
        $levels = 0;

        $path = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'fixtures';

        $vendorDirectory = realpath($path . '/vendor');

        while ($vendorDirectory === false) {
            $vendorDirectory = realpath(dirname($path, ++$levels) . '/vendor');
        }

        if ($vendorDirectory === '') {
            Assert::fail('Could not find vendor directory');
        }

        foreach (new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS) as $fixtureDirectory) {
            if (! $fixtureDirectory instanceof SplFileInfo) {
                continue;
            }

            if (! $fixtureDirectory->isDir()) {
                continue;
            }

            yield $fixtureDirectory->getBasename() => [
                new Fixture(
                    $fixtureDirectory->getRealPath(),
                    $vendorDirectory
                ),
            ];
        }
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
