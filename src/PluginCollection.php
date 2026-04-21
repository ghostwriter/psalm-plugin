<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use Psalm\Plugin\PluginEntryPointInterface;
use RuntimeException;

use function array_key_exists;
use function array_keys;
use function is_a;
use function sprintf;

final class PluginCollection
{
    /** @var array<class-string<PluginEntryPointInterface>,bool> */
    private array $plugins = [];

    /**
     * @param class-string<PluginEntryPointInterface> $plugin
     *
     * @throws RuntimeException
     */
    public function add(string $plugin): void
    {
        if (! is_a($plugin, PluginEntryPointInterface::class, true)) {
            throw new RuntimeException(sprintf(
                'Plugin "%s" does not implement "%s".',
                $plugin,
                PluginEntryPointInterface::class
            ));
        }

        if (! array_key_exists($plugin, $this->plugins)) {
            $this->plugins[$plugin] = true;
        }
    }

    public function reset(): void
    {
        $this->plugins = [];
    }

    /** @return list<class-string<PluginEntryPointInterface>> */
    public function toArray(): array
    {
        return array_keys($this->plugins);
    }
}
