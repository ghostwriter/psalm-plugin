<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Psalm\Internal;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Psalm\Internal\IncludeCollector;
use Throwable;

/**
 * @see IncludeCollectorFactoryTest
 *
 * @implements FactoryInterface<IncludeCollector>
 */
final readonly class IncludeCollectorFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): IncludeCollector
    {
        return new IncludeCollector();
    }
}
