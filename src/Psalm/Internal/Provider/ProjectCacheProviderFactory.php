<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Psalm\Internal\Provider;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Psalm\Internal\Provider\ProjectCacheProvider;
use Throwable;

/**
 * @see ProjectCacheProviderFactoryTest
 *
 * @implements FactoryInterface<ProjectCacheProvider>
 */
final readonly class ProjectCacheProviderFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): ProjectCacheProvider
    {
        return new ProjectCacheProvider();
    }
}
