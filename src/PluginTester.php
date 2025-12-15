<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use Ghostwriter\Container\Container;
use PHPUnit\Framework\Assert;
use Psalm\Plugin\PluginEntryPointInterface;

final readonly class PluginTester
{
    public function __construct(
        private Psalm $psalm
    ) {}

    public static function new(): self
    {
        return Container::getInstance()->get(self::class);
    }

    /** @param class-string<PluginEntryPointInterface> $class */
    public function testPlugin(string $class, Fixture $fixture): void
    {
        $this->psalm->run($fixture, $class);
        Assert::assertTrue(true);
    }
}
