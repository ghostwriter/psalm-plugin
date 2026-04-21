<?php

declare(strict_types=1);

use Ghostwriter\Container\Container;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Shell\Interface\ResultInterface;
use Ghostwriter\Shell\Interface\ShellInterface;

if (! \function_exists('container')) {
    function container(): ContainerInterface
    {
        return Container::getInstance();
    }
}

if (! \function_exists('shell')) {
    function shell(): ShellInterface
    {
        return \container()->get(ShellInterface::class);
    }
}

if (! \function_exists('gcs')) {
    function gcs(string $workspace, string ...$arguments): ResultInterface
    {
        return \shell()->execute(command: 'gcs', arguments: $arguments, workingDirectory: $workspace);
    }
}
