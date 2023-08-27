<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

final class Plugin implements PluginEntryPointInterface
{
    public function __invoke(RegistrationInterface $registration, \SimpleXMLElement $config = null): void
    {
        $hookDirectory = __DIR__.'/Hook/';

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $hookDirectory,
                \RecursiveDirectoryIterator::SKIP_DOTS
            )
        );

        foreach ($files as $file) {
            if (!$file instanceof \SplFileInfo) {
                continue;
            }

            if (!$file->isFile()) {
                continue;
            }

            $realPath = $file->getRealPath();

            $stringPosition = strripos($realPath, '/Hook/');
            if ($stringPosition === false) {
                continue;
            }

            $className  = basename(
                str_replace('/', '\\', substr($realPath, $stringPosition)),
                '.php'
            );

            /** @var class-string $class */
            $class = __NAMESPACE__ . $className;

            if (!class_exists($class)) {
                return;
            }

            $registration->registerHooksFromClass($class);
        }
    }
}
