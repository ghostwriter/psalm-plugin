<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Ghostwriter\Shell\Interface\ShellInterface;
use Psalm\Config;
use Psalm\Internal\Analyzer\ProjectAnalyzer;
use Psalm\Internal\IncludeCollector;
use Psalm\Internal\Provider\FileProvider;
use Psalm\Internal\Provider\ParserCacheProvider;
use Psalm\Internal\Provider\Providers;
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
        private ShellInterface $shell,
        private FilesystemInterface $filesystem,
    ) {}

    public function run(Fixture $fixture, string $class): void
    {
        $filesystem = $this->filesystem;
        $workspace = $fixture->workspace;

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

        $vendorDirectory = $fixture->vendorDirectory;

        $filename = (new ReflectionClass($class))->getFileName();

        $command = implode(DIRECTORY_SEPARATOR, [$vendorDirectory, 'bin', 'psalm']);
        $psalmXml = implode(DIRECTORY_SEPARATOR, [$workspace, 'psalm.xml.dist']);

        dump([
            'command' => $command,
            'psalmXml' => $psalmXml,
            'workspace' => $workspace,
            'pluginFilename' => $filename,
            'class' => $class,
            'vendorDirectory' => $vendorDirectory,
        ]);

        foreach ($this->filesystem->recursiveIterator($workspace) as $file) {
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

            if (str_contains($sourcePath, $workspace . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR)) {
                continue;
            }

            $destinationPath = str_replace(
                $workspace . DIRECTORY_SEPARATOR,
                $workspace . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR,
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

        $psalmXml = implode(DIRECTORY_SEPARATOR, [$workspace, 'psalm.xml']);
        $this->filesystem->write(path: $psalmXml, contents: $psalmConfigXml);

        $psalmConfig = Config::loadFromXML($workspace, $psalmConfigXml);
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
            workingDirectory: $workspace
        );

        $filesystem->write($workspace . DIRECTORY_SEPARATOR . 'psalm-summary.xml', $result->stdout());

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
        //            workingDirectory: $workspace
        //        );

        //        dump($result);
        $filesystem->delete($psalmXml);
    }
}
