<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Path;

final readonly class FixtureDirectory
{
    public function __construct(
        private WorkspaceDirectory $workspaceDirectory,
        private VendorDirectory $vendorDirectory,
    ) {}

    public static function new(WorkspaceDirectory $workspaceDirectory, VendorDirectory $vendorDirectory): self
    {
        return new self(workspaceDirectory: $workspaceDirectory, vendorDirectory: $vendorDirectory);
    }

    public function vendorDirectory(): VendorDirectory
    {
        return $this->vendorDirectory;
    }

    public function workspaceDirectory(): WorkspaceDirectory
    {
        return $this->workspaceDirectory;
    }
}
