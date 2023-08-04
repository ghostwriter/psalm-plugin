<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;

final class Plugin implements PluginEntryPointInterface
{
    public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null): void
    {
        foreach (glob(__DIR__ . '/Hook/*.php') ?: [] as $file) {
            /** @var class-string $class */
            $class = __NAMESPACE__ . '\\Hook\\' . basename($file, '.php');

            if (! class_exists($class)) {
                return;
            }

            $registration->registerHooksFromClass($class);
        }
    }
}
