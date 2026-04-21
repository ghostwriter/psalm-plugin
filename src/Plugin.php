<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use Ghostwriter\Container\Container;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Override;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;

use SplFileInfo;

use const DIRECTORY_SEPARATOR;

use const PHP_EOL;
use const STDERR;

use function class_exists;
use function fwrite;
use function implode;
use function mb_strripos;
use function mb_substr;
use function sprintf;
use function str_replace;

final class Plugin implements PluginEntryPointInterface
{
    #[Override]
    public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null): void
    {
        $container = Container::getInstance();

        $hookDirectory = implode(DIRECTORY_SEPARATOR, [__DIR__, 'Hook']);
        $pattern = sprintf('#%sHook%s.*\.php$#iu', DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR);
        foreach ($container->get(FilesystemInterface::class)->regexIterator($hookDirectory, $pattern) as $file) {
            if (! $file instanceof SplFileInfo) {
                continue;
            }

            if (! $file->isFile()) {
                continue;
            }

            $realPath = $file->getRealPath();

            $stringPosition = mb_strripos($realPath, DIRECTORY_SEPARATOR . 'Hook' . DIRECTORY_SEPARATOR);
            if (false === $stringPosition) {
                continue;
            }

            $className  = mb_substr(
                str_replace(DIRECTORY_SEPARATOR, '\\', mb_substr($realPath, $stringPosition)),
                0,
                -4
            );

            /** @var class-string $class */
            $class = __NAMESPACE__ . $className;

            if (! class_exists($class)) {
                fwrite(STDERR, sprintf('Unable to load psalm plugin class "%s".', $class) . PHP_EOL);

                return;
            }

            $registration->registerHooksFromClass($class);
        }
    }
}
