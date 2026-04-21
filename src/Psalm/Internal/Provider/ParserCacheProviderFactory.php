<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Psalm\Internal\Provider;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Psalm\Config;
use Psalm\Internal\Composer;
use Psalm\Internal\Provider\ParserCacheProvider;
use RuntimeException;
use Throwable;

use function getcwd;

/**
 * @see ParserCacheProviderFactoryTest
 *
 * @implements FactoryInterface<ParserCacheProvider>
 */
final readonly class ParserCacheProviderFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): ParserCacheProvider
    {
        $composerLockFile = Composer::getLockFilePath(
            getcwd() ?: throw new RuntimeException('Unable to determine current working directory.'),
        );

        return new ParserCacheProvider($container->get(Config::class), $composerLockFile);
    }
}
