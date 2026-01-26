<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use Ghostwriter\Filesystem\Filesystem;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;
use SplFileInfo;

use const DIRECTORY_SEPARATOR;

use function class_exists;
use function implode;
use function mb_strripos;
use function mb_substr;
use function str_replace;

final class Plugin implements PluginEntryPointInterface
{
    public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null): void
    {
        $hookDirectory = implode(DIRECTORY_SEPARATOR, [__DIR__, 'Hook']);

        foreach (Filesystem::new()->recursiveIterator($hookDirectory) as $file) {
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
                return;
            }

            $registration->registerHooksFromClass($class);
        }
    }
}
