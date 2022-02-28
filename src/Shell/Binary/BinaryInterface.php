<?php

declare(strict_types=1);

namespace Jascha030\CLI\Shell\Binary;

use Jascha030\CLI\Shell\ShellInterface;

interface BinaryInterface extends ShellInterface
{
    public function getName(): string;

    public function getPath(): string;

    public function getVersion(): ?string;
}
