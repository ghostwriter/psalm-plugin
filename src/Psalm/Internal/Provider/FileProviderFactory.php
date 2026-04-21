<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Psalm\Internal\Provider;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Psalm\Internal\Provider\FileProvider;
use Throwable;

/**
 * @see FileProviderFactoryTest
 *
 * @implements FactoryInterface<FileProvider>
 */
final readonly class FileProviderFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): FileProvider
    {
        return new FileProvider();
    }
}
