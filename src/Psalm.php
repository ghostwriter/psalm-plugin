<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Ghostwriter\PsalmPlugin\Path\FixtureDirectory;
use Ghostwriter\Shell\Interface\ShellInterface;
use Psalm\Config;
use Psalm\Internal\IncludeCollector;
use Psalm\Plugin\PluginEntryPointInterface;
use ReflectionClass;
use SplFileInfo;

use const DIRECTORY_SEPARATOR;
use const PHP_EOL;

use function dump;
use function implode;
use function sprintf;
use function str_contains;
use function str_ends_with;
use function str_replace;

final readonly class Psalm
{
    private const string BIN = 'vendor/bin/psalm';

    public function __construct(
        private ContainerInterface $container,
        private ShellInterface $shell,
        private FilesystemInterface $filesystem,
        private PluginCollection $pluginCollection,
    ) {}

    public function reset(): void
    {
        $this->pluginCollection->reset();
        $this->container->reset();
    }

    public function run(FixtureDirectory $fixture): void
    {
        $filesystem = $this->filesystem;
        $vendorDirectory = $fixture->vendorDirectory()->toString();
        $workspaceDirectory = $fixture->workspaceDirectory()->toString();
        $class = Plugin::class;

        $filename = (new ReflectionClass($class))->getFileName();

        $command = implode(DIRECTORY_SEPARATOR, [$vendorDirectory, 'bin', 'psalm']);
        $psalmXml = implode(DIRECTORY_SEPARATOR, [$workspaceDirectory, 'psalm.xml.dist']);

        //        $files = [
        //            'json-summary.json',
        //            'psalm-summary.json',
        //            'psalm-summary.xml',
        //        ];
        //        foreach ($files as $file) {
        //            $path = $workspace . DIRECTORY_SEPARATOR . $file;
        //            if ($filesystem->missing($path)) {
        //                continue;
        //            }
        //            $filesystem->delete($path);
        //        }
        //        return;

        dump([
            'class' => $class,
            'command' => $command,
            'pluginFilename' => $filename,
            'psalmXml' => $psalmXml,
            'vendorDirectory' => $vendorDirectory,
            'workspace' => $workspaceDirectory,
        ]);

        foreach ($this->filesystem->regexIterator($workspaceDirectory, '#\.php$#u') as $file) {
            if (! $file instanceof SplFileInfo) {
                continue;
            }

            if (! $file->isFile()) {
                continue;
            }

            $sourcePath = $file->getRealPath();
            if (false === $sourcePath) {
                continue;
            }

            if (! str_ends_with($sourcePath, '.php')) {
                continue;
            }

            if (str_contains($sourcePath, $workspaceDirectory . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR)) {
                continue;
            }

            $destinationPath = str_replace(
                $workspaceDirectory . DIRECTORY_SEPARATOR,
                $workspaceDirectory . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR,
                $sourcePath
            );

            $this->filesystem->write($destinationPath, $filesystem->read($sourcePath));
            $this->filesystem->delete($sourcePath);
        }

        //        $indent = str_repeat(' ', 4);
        $psalmConfigXml = implode(PHP_EOL, [
            '<?xml version="1.0"?>',
            '<psalm',
            '    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
            '    xmlns="https://getpsalm.org/schema/config"',
            '    xsi:schemaLocation="https://getpsalm.org/schema/config vendor/vimeo/psalm/config.xsd"',
            '    errorLevel="1"',
            '>',
            '    <projectFiles>',
            '        <directory name="src" />',
            '        <ignoreFiles>',
            '            <directory name="../../../vendor" />',
            '        </ignoreFiles>',
            '    </projectFiles>',
            '    <plugins>',
            sprintf('        <plugin filename="%s" />', $filename),
            '    </plugins>',
            '</psalm>',
        ]);

        $psalmXml = implode(DIRECTORY_SEPARATOR, [$workspaceDirectory, 'psalm.xml']);
        $this->filesystem->write(path: $psalmXml, contents: $psalmConfigXml);

        $psalmConfig = Config::loadFromXML($workspaceDirectory, $psalmConfigXml);
        $psalmConfig->setIncludeCollector(new IncludeCollector());

        //        $projectAnalyzer = new ProjectAnalyzer(
        //            $psalmConfig,
        //            new Providers(
        //                new FileProvider(),
        // //                new ParserCacheProvider($psalmConfig),
        //            ),
        //        );
        // //        $projectAnalyzer->setPhpVersion('7.3', 'tests');
        //        $projectAnalyzer->checkDir($workspace);

        //        require_once $filename;

        //        $plugin = '--plugin=' . escapeshellarg($filename);

        //        $result = $this->shell->execute(
        //            command: $command,
        //            arguments: [
        //                '--shepherd',
        //                '--no-diff',
        //                '--no-cache',
        //                '--output-format=json-summary',
        //            ],
        //            workingDirectory: $workspace
        //        );
        //
        //        $filesystem->write($workspace . DIRECTORY_SEPARATOR . 'psalm-summary.json', $result->stdout());
        $result = $this->shell->execute(
            command: $command,
            arguments: ['--shepherd', '--no-diff', '--no-cache', '--output-format=xml'],
            workingDirectory: $workspaceDirectory
        );

        $filesystem->write($workspaceDirectory . DIRECTORY_SEPARATOR . 'psalm-summary.xml', $result->stdout());

        //        $result = $this->shell->execute(
        //            command: $command,
        //            arguments: [
        //                '--shepherd',
        //                '--no-diff',
        //                '--no-cache',
        // //                '--output-format=json-summary',
        // //                '--output-format=xml',
        // //                '--output-format=junit',
        // //                '--output-format=json',
        //                '--output-format=phpstorm',
        // //                $plugin
        //            ],
        //            workingDirectory: $workspaceDirectory
        //        );

        //        dump($result);
        $filesystem->delete($psalmXml);
    }

    /** @param class-string<PluginEntryPointInterface> ...$plugins */
    public function withPlugins(string ...$plugins): self
    {
        foreach ($plugins as $plugin) {
            $this->pluginCollection->add($plugin);
        }

        return $this;
    }
}
