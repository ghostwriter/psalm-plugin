<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use FilesystemIterator;
use Generator;
use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;
use Ghostwriter\PsalmPlugin\AbstractHook;
use Ghostwriter\PsalmPlugin\Hook\FixParamNameMismatchHook;
use Ghostwriter\PsalmPlugin\Hook\Ghostwriter\PsalmPlug\SuppressInternalClassHook;
use Ghostwriter\PsalmPlugin\Hook\Ghostwriter\PsalmPlug\SuppressInternalMethodHook;
use Ghostwriter\PsalmPlugin\Hook\Ghostwriter\PsalmPlug\SuppressInternalPropertyHook;
use Ghostwriter\PsalmPlugin\Hook\Ghostwriter\PsalmPlug\SuppressPossiblyUnusedMethodHook;
use Ghostwriter\PsalmPlugin\Hook\PHPUnit\Framework\TestCase\SuppressMissingThrowsDocblockHook;
use Ghostwriter\PsalmPlugin\Hook\PHPUnit\Framework\TestCase\SuppressPropertyNotSetInConstructorHook;
use Ghostwriter\PsalmPlugin\Hook\PHPUnit\Framework\TestCase\SuppressUnusedClassHook;
use Ghostwriter\PsalmPlugin\Hook\Psr\Container\ContainerInterface\GetMethodAfterMethodCallAnalysisHook;
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
#[CoversClass(SuppressMissingThrowsDocblockHook::class)]
#[CoversClass(SuppressUnusedClassHook::class)]
#[CoversClass(SuppressPropertyNotSetInConstructorHook::class)]
#[CoversClass(SuppressInternalClassHook::class)]
#[CoversClass(SuppressInternalMethodHook::class)]
#[CoversClass(SuppressPossiblyUnusedMethodHook::class)]
#[CoversClass(SuppressInternalPropertyHook::class)]
#[CoversClass(GetMethodAfterMethodCallAnalysisHook::class)]
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
