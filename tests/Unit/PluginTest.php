<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Tests\Unit;

use Generator;
use Ghostwriter\Container\Container;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Filesystem\Filesystem;
use Ghostwriter\PsalmPlugin\AbstractBeforeAddIssueEventHook;
use Ghostwriter\PsalmPlugin\AbstractHook;
use Ghostwriter\PsalmPlugin\Container\PsalmPluginProvider;
use Ghostwriter\PsalmPlugin\Hook\FixParamNameMismatchHook;
use Ghostwriter\PsalmPlugin\Hook\Ghostwriter\PsalmPlug\SuppressInternalClassHook;
use Ghostwriter\PsalmPlugin\Hook\Ghostwriter\PsalmPlug\SuppressInternalMethodHook;
use Ghostwriter\PsalmPlugin\Hook\Ghostwriter\PsalmPlug\SuppressInternalPropertyHook;
use Ghostwriter\PsalmPlugin\Hook\Ghostwriter\PsalmPlug\SuppressPossiblyUnusedMethodHook;
use Ghostwriter\PsalmPlugin\Hook\PHPUnit\Framework\TestCase\SuppressMissingThrowsDocblockHook;
use Ghostwriter\PsalmPlugin\Hook\PHPUnit\Framework\TestCase\SuppressPropertyNotSetInConstructorHook;
use Ghostwriter\PsalmPlugin\Hook\PHPUnit\Framework\TestCase\SuppressUnusedClassHook;
use Ghostwriter\PsalmPlugin\Hook\Psr\Container\ContainerInterface\GetMethodAfterMethodCallAnalysisHook;
use Ghostwriter\PsalmPlugin\Path\FixtureDirectory;
use Ghostwriter\PsalmPlugin\Path\ProjectDirectory;
use Ghostwriter\PsalmPlugin\Path\VendorDirectory;
use Ghostwriter\PsalmPlugin\Path\WorkspaceDirectory;
use Ghostwriter\PsalmPlugin\Plugin;
use Ghostwriter\PsalmPlugin\PluginTester;
use Ghostwriter\PsalmPlugin\Psalm;
use Override;
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
#[CoversClass(Plugin::class)]
#[CoversClass(PluginTester::class)]
#[CoversClass(Psalm::class)]
#[CoversClass(PsalmPluginProvider::class)]
#[CoversClass(SuppressInternalClassHook::class)]
#[CoversClass(SuppressInternalMethodHook::class)]
#[CoversClass(SuppressInternalPropertyHook::class)]
#[CoversClass(SuppressMissingThrowsDocblockHook::class)]
#[CoversClass(SuppressPossiblyUnusedMethodHook::class)]
#[CoversClass(SuppressPropertyNotSetInConstructorHook::class)]
#[CoversClass(SuppressUnusedClassHook::class)]
#[CoversClass(ProjectDirectory::class)]
#[CoversClass(FixtureDirectory::class)]
#[CoversClass(VendorDirectory::class)]
#[CoversClass(WorkspaceDirectory::class)]
final class PluginTest extends TestCase
{
    private ContainerInterface $container;

    private PluginTester $pluginTester;

    #[Override]
    protected function setUp(): void
    {
        $this->container = Container::getInstance();
        $this->pluginTester = PluginTester::new(Plugin::class);
    }

    #[Override]
    protected function tearDown(): void
    {
        $this->pluginTester->reset();
    }

    #[DataProvider('providePluginCases')]
    public function testPlugin(FixtureDirectory $fixture): void
    {
        PluginTester::new(Plugin::class)->run($fixture);
    }

    /**
     * @throws Throwable
     *
     * @return Generator<string,list<FixtureDirectory>>
     *
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

        $vendorDirectory = VendorDirectory::new($vendorDirectory);

        foreach (Filesystem::new()->filesystemIterator($path) as $fixtureDirectory) {
            if (! $fixtureDirectory instanceof SplFileInfo) {
                continue;
            }

            if (! $fixtureDirectory->isDir()) {
                continue;
            }

            yield $fixtureDirectory->getBasename() => [
                FixtureDirectory::new(WorkspaceDirectory::new($fixtureDirectory->getRealPath()), $vendorDirectory),
            ];
        }
    }
}
