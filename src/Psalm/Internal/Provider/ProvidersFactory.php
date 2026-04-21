<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Psalm\Internal\Provider;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Psalm\Internal\Provider\ClassLikeStorageCacheProvider;
use Psalm\Internal\Provider\FileProvider;
use Psalm\Internal\Provider\FileReferenceCacheProvider;
use Psalm\Internal\Provider\FileStorageCacheProvider;
use Psalm\Internal\Provider\ParserCacheProvider;
use Psalm\Internal\Provider\ProjectCacheProvider;
use Psalm\Internal\Provider\Providers;
use Throwable;

/**
 * @see ProvidersFactoryTest
 *
 * @implements FactoryInterface<Providers>
 */
final readonly class ProvidersFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): Providers
    {
        return new Providers(
            $container->get(FileProvider::class),
            $container->get(ParserCacheProvider::class),
            $container->get(FileStorageCacheProvider::class),
            $container->get(ClassLikeStorageCacheProvider::class),
            $container->get(FileReferenceCacheProvider::class),
            $container->get(ProjectCacheProvider::class)
        );
    }
}
