<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Psalm\Progress;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Psalm\Progress\Progress;
use Psalm\Progress\VoidProgress;
use Throwable;

/**
 * @see ProgressFactoryTest
 *
 * @implements FactoryInterface<Progress>
 */
final readonly class ProgressFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): Progress
    {
        return new VoidProgress();
    }
}
