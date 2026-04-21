<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Psalm\Internal\PluginManager;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Psalm\Internal\PluginManager\ConfigFile;
use RuntimeException;
use Throwable;

use function getcwd;

/**
 * @see ConfigFileFactoryTest
 *
 * @implements FactoryInterface<ConfigFile>
 */
final readonly class ConfigFileFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): ConfigFile
    {
        return new ConfigFile(
            getcwd() ?: throw new RuntimeException('Unable to determine current working directory.'),
            null
        );
    }
}
