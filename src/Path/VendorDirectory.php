<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Path;

final readonly class VendorDirectory
{
    public function __construct(
        private string $path,
    ) {}

    public static function new(string $path): self
    {
        return new self(path: $path);
    }

    public function toString(): string
    {
        return $this->path;
    }
}
