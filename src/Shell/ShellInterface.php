<?php

declare(strict_types=1);

namespace Jascha030\CLI\Shell;

interface ShellInterface
{
    public function run(string $command, ?string $cwd = null, ?callable $onError = null): string;

    public function runAsUser(string $command, ?string $cwd = null, ?callable $onError = null): string;

    public function quietly(string $command, ?string $cwd = null, ?callable $onError = null): void;

    public function quietlyAsUser(string $command, ?string $cwd = null, ?callable $onError = null): void;
}
