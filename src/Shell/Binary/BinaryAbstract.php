<?php

declare(strict_types=1);

namespace Jascha030\CLI\Shell\Binary;

abstract class BinaryAbstract implements BinaryInterface
{
    public function __construct(
        private string $name,
        private string $path,
        private ?string $version = null
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }
}