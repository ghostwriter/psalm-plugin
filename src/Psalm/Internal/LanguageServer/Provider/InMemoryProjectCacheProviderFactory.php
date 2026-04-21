<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Psalm\Internal\LanguageServer\Provider;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Psalm\Internal\LanguageServer\Provider\InMemoryProjectCacheProvider;
use Throwable;

/**
 * @see InMemoryProjectCacheProviderFactoryTest
 *
 * @implements FactoryInterface<InMemoryProjectCacheProvider>
 */
final readonly class InMemoryProjectCacheProviderFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): InMemoryProjectCacheProvider
    {
        return new InMemoryProjectCacheProvider();
    }
}
