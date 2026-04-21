<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\EventDispatcher;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\ExtensionInterface;
use Ghostwriter\EventDispatcher\Interface\ListenerProviderInterface;
use Ghostwriter\PsalmPlugin\EventDispatcher\Listener\DumpListener;
use Override;
use Throwable;

use function assert;

/**
 * @see ListenerProviderExtensionTest
 *
 * @implements ExtensionInterface<ListenerProviderInterface>
 */
final readonly class ListenerProviderExtension implements ExtensionInterface
{
    private const array EVENT_LISTENERS = [
        // [ event => [ listener, ... ]
        'object' => [DumpListener::class],
    ];

    /**
     * @param ListenerProviderInterface $service
     *
     * @throws Throwable
     */
    #[Override]
    public function __invoke(ContainerInterface $container, object $service): void
    {
        assert($service instanceof ListenerProviderInterface);
        foreach (self::EVENT_LISTENERS as $event => $listeners) {
            foreach ($listeners as $listener) {
                $service->listen($event, $listener);
            }
        }
    }
}
