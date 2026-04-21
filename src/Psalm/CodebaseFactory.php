<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Psalm;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Psalm\Codebase;
use Psalm\Config;
use Psalm\Internal\Provider\Providers;
use Psalm\Progress\Progress;
use Throwable;

/**
 * @see CodebaseFactoryTest
 *
 * @implements FactoryInterface<Codebase>
 */
final readonly class CodebaseFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): Codebase
    {
        return new Codebase(
            $container->get(Config::class),
            $container->get(Providers::class),
            $container->get(Progress::class),
        );
    }
}
