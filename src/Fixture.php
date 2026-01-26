<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin;

final readonly class Fixture
{
    public function __construct(
        public string $workspace,
        public string $vendorDirectory,
    ) {}

    public static function new(string $workspace, string $vendorDirectory): self
    {
        return new self(workspace: $workspace, vendorDirectory: $vendorDirectory);
    }
}
