<?php

declare(strict_types=1);

namespace Jascha030\CLI\Shell\Binary;

interface InvokableBinaryInterface extends BinaryInterface
{
    public function __invoke(string $command, ?string $cwd = null, ?callable $onError = null): string;
}
