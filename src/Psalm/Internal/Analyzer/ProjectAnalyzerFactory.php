<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Psalm\Internal\Analyzer;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Psalm\Config;
use Psalm\Internal\Analyzer\ProjectAnalyzer;
use Psalm\Internal\Provider\Providers;
use Psalm\Report\ReportOptions;
use Throwable;

/**
 * @see ProjectAnalyzerFactoryTest
 *
 * @implements FactoryInterface<ProjectAnalyzer>
 */
final readonly class ProjectAnalyzerFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): ProjectAnalyzer
    {
        return new ProjectAnalyzer(
            $container->get(Config::class),
            $container->get(Providers::class),
            $container->get(ReportOptions::class)
        );
    }
}
