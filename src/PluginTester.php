<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use Ghostwriter\Container\Container;
use Ghostwriter\PsalmPlugin\Path\FixtureDirectory;
use PHPUnit\Framework\Assert;
use Psalm\Plugin\PluginEntryPointInterface;

final readonly class PluginTester
{
    /** @param list<class-string<PluginEntryPointInterface>> $plugins */
    public function __construct(
        private Psalm $psalm,
        private array $plugins,
    ) {}

    public static function new(string ...$plugins): self
    {
        return Container::getInstance()->build(self::class, [
            'plugins' => $plugins,
        ]);
    }

    public function reset(): void
    {
        $this->psalm->reset();
    }

    public function run(FixtureDirectory $fixture): void
    {
        $this->psalm->withPlugins(...$this->plugins)->run($fixture);

        Assert::assertTrue(true);
    }
}
