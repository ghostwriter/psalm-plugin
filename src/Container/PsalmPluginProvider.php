<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Container;

use Ghostwriter\Container\Interface\BuilderInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Service\Provider\AbstractProvider;
use Ghostwriter\EventDispatcher\Interface\ListenerProviderInterface;
use Ghostwriter\PsalmPlugin\Configuration\PsalmPluginConfiguration;
use Ghostwriter\PsalmPlugin\Configuration\PsalmPluginConfigurationInterface;
use Ghostwriter\PsalmPlugin\Container\Ghostwriter\PsalmPlugin\Configuration\PsalmPluginConfigurationExtension;
use Ghostwriter\PsalmPlugin\Container\Ghostwriter\PsalmPlugin\Configuration\PsalmPluginConfigurationFactory;
use Ghostwriter\PsalmPlugin\EventDispatcher\ListenerProviderExtension;
use Ghostwriter\PsalmPlugin\Psalm\CodebaseFactory;
use Ghostwriter\PsalmPlugin\Psalm\ConfigFactory;
use Ghostwriter\PsalmPlugin\Psalm\Internal\Analyzer\ProjectAnalyzerFactory;
use Ghostwriter\PsalmPlugin\Psalm\Internal\IncludeCollectorFactory;
use Ghostwriter\PsalmPlugin\Psalm\Internal\LanguageServer\Provider\InMemoryProjectCacheProviderFactory;
use Ghostwriter\PsalmPlugin\Psalm\Internal\PluginManager\ConfigFileFactory;
use Ghostwriter\PsalmPlugin\Psalm\Internal\Provider\ClassLikeStorageCacheProviderFactory;
use Ghostwriter\PsalmPlugin\Psalm\Internal\Provider\FileProviderFactory;
use Ghostwriter\PsalmPlugin\Psalm\Internal\Provider\FileReferenceCacheProviderFactory;
use Ghostwriter\PsalmPlugin\Psalm\Internal\Provider\FileStorageCacheProviderFactory;
use Ghostwriter\PsalmPlugin\Psalm\Internal\Provider\ParserCacheProviderFactory;
use Ghostwriter\PsalmPlugin\Psalm\Internal\Provider\ProjectCacheProviderFactory;
use Ghostwriter\PsalmPlugin\Psalm\Internal\Provider\ProvidersFactory;
use Ghostwriter\PsalmPlugin\Psalm\Progress\ProgressFactory;
use Override;
use Psalm\Codebase;
use Psalm\Config;
use Psalm\Internal\Analyzer\ProjectAnalyzer;
use Psalm\Internal\IncludeCollector;
use Psalm\Internal\LanguageServer\Provider\InMemoryProjectCacheProvider;
use Psalm\Internal\PluginManager\ConfigFile;
use Psalm\Internal\Provider\ClassLikeStorageCacheProvider;
use Psalm\Internal\Provider\FileProvider;
use Psalm\Internal\Provider\FileReferenceCacheProvider;
use Psalm\Internal\Provider\FileStorageCacheProvider;
use Psalm\Internal\Provider\ParserCacheProvider;
use Psalm\Internal\Provider\ProjectCacheProvider;
use Psalm\Internal\Provider\Providers;
use Psalm\Progress\Progress;
use Throwable;

/**
 * @see PsalmPluginProviderTest
 */
final class PsalmPluginProvider extends AbstractProvider
{
    /** @throws Throwable */
    #[Override]
    public function boot(ContainerInterface $container): void {}

    /** @throws Throwable */
    #[Override]
    public function register(BuilderInterface $builder): void
    {
        //        $builder->alias(PsalmPluginConfigurationInterface::class, PsalmPluginConfiguration::class);

        $builder->extend(ListenerProviderInterface::class, ListenerProviderExtension::class);
        //        $builder->extend(PsalmPluginConfigurationInterface::class, PsalmPluginConfigurationExtension::class);

        $builder->factory(ClassLikeStorageCacheProvider::class, ClassLikeStorageCacheProviderFactory::class);
        $builder->factory(Codebase::class, CodebaseFactory::class);
        $builder->factory(ConfigFile::class, ConfigFileFactory::class);
        $builder->factory(FileProvider::class, FileProviderFactory::class);
        $builder->factory(FileReferenceCacheProvider::class, FileReferenceCacheProviderFactory::class);
        $builder->factory(FileStorageCacheProvider::class, FileStorageCacheProviderFactory::class);
        $builder->factory(InMemoryProjectCacheProvider::class, InMemoryProjectCacheProviderFactory::class);
        $builder->factory(IncludeCollector::class, IncludeCollectorFactory::class);
        $builder->factory(ParserCacheProvider::class, ParserCacheProviderFactory::class);
        $builder->factory(ProjectAnalyzer::class, ProjectAnalyzerFactory::class);
        $builder->factory(ProjectCacheProvider::class, ProjectCacheProviderFactory::class);
        $builder->factory(Providers::class, ProvidersFactory::class);
        //        $builder->factory(PsalmPluginConfiguration::class, PsalmPluginConfigurationFactory::class);
        $builder->factory(Progress::class, ProgressFactory::class);
        $builder->factory(Config::class, ConfigFactory::class);
    }
}
