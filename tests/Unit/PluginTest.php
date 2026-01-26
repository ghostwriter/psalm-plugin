<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use Generator;
use Ghostwriter\Filesystem\Filesystem;
use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;
use Ghostwriter\PsalmPlugin\AbstractHook;
use Ghostwriter\PsalmPlugin\Fixture;
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
use Ghostwriter\PsalmPlugin\PluginTester;
use Ghostwriter\PsalmPlugin\Psalm;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Throwable;

use const DIRECTORY_SEPARATOR;

use function dirname;
use function implode;
use function realpath;

#[CoversClass(AbstractBeforeAddIssueEventHook::class)]
#[CoversClass(AbstractHook::class)]
#[CoversClass(FixParamNameMismatchHook::class)]
#[CoversClass(GetMethodAfterMethodCallAnalysisHook::class)]
#[CoversClass(Psalm::class)]
#[CoversClass(Plugin::class)]
#[CoversClass(PluginTester::class)]
#[CoversClass(SuppressInternalClassHook::class)]
#[CoversClass(SuppressInternalMethodHook::class)]
#[CoversClass(SuppressInternalPropertyHook::class)]
#[CoversClass(SuppressMissingThrowsDocblockHook::class)]
#[CoversClass(SuppressPossiblyUnusedMethodHook::class)]
#[CoversClass(SuppressPropertyNotSetInConstructorHook::class)]
#[CoversClass(SuppressUnusedClassHook::class)]
final class PluginTest extends TestCase
{
    private PluginTester $pluginTester;

    protected function setUp(): void
    {
        $this->pluginTester = PluginTester::new();
    }

    #[DataProvider('providePluginCases')]
    public function testPlugin(Fixture $fixture): void
    {
        $this->pluginTester->testPlugin(Plugin::class, $fixture);
    }

    /**
     * @throws Throwable
     *
     * @return Generator<string,list<Fixture>>
     */
    public static function providePluginCases(): iterable
    {
        $path = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'Fixture']);

        $levels = 0;

        do {
            $vendorDirectory = realpath(dirname($path, ++$levels) . DIRECTORY_SEPARATOR . 'vendor');
        } while (false === $vendorDirectory);

        if ('' === $vendorDirectory) {
            Assert::fail('Could not find vendor directory');
        }

        foreach (Filesystem::new()->filesystemIterator($path) as $fixtureDirectory) {
            if (! $fixtureDirectory instanceof SplFileInfo) {
                continue;
            }

            if (! $fixtureDirectory->isDir()) {
                continue;
            }

            yield $fixtureDirectory->getBasename() => [
                Fixture::new($fixtureDirectory->getRealPath(), $vendorDirectory),
            ];
        }
    }
}
