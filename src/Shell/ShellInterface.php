<?php

declare(strict_types=1);

namespace Jascha030\CLI\Shell;

interface ShellInterface
{
    /**
     * Run a shell command.
     */
    public function run(string $command, ?string $cwd = null, ?callable $onError = null): string;

    /**
     * Run a shell command with sudo user capabilities.
     */
    public function runAsUser(string $command, ?string $cwd = null, ?callable $onError = null): string;

    /**
     * Run a shell command without writing output to the STDOUT or php.
     */
    public function quietly(string $command, ?string $cwd = null, ?callable $onError = null): void;

    /**
     * Run a shell command with sudo user capabilities,  without writing output to the STDOUT or php.
     */
    public function quietlyAsUser(string $command, ?string $cwd = null, ?callable $onError = null): void;
}
