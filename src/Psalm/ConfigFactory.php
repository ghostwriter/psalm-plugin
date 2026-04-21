<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Psalm;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Psalm\Config;
use Throwable;

/**
 * @see ConfigFactoryTest
 *
 * @implements FactoryInterface<Config>
 */
final readonly class ConfigFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): Config
    {
        return Config::getInstance();
    }
}
