<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use FilesystemIterator;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;
use SplFileInfo;

final class Plugin implements PluginEntryPointInterface
{
    public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null): void
    {
        foreach (new FilesystemIterator(__DIR__ . DIRECTORY_SEPARATOR . 'Hook', FilesystemIterator::SKIP_DOTS) as $file) {
            if (! $file instanceof SplFileInfo) {
                continue;
            }

            if (! $file->isFile()) {
                continue;
            }

            if (! str_ends_with($file->getPathname(), '.php')) {
                continue;
            }

            /** @var class-string $class */
            $class = sprintf('%s\\Hook\\%s', __NAMESPACE__, $file->getBasename('.php'));

            if (! class_exists($class)) {
                return;
            }

            $registration->registerHooksFromClass($class);
        }
    }
}
