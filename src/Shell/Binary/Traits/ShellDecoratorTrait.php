<?php

declare(strict_types=1);

namespace Jascha030\CLI\Shell\Binary\Traits;

use Jascha030\CLI\Shell\ShellInterface;

trait ShellDecoratorTrait
{
    abstract public function getShell(): ShellInterface;

    public function run(string $command, ?string $cwd = null, ?callable $onError = null): string
    {
        return $this->getShell()->run($command, $cwd, $onError);
    }

    public function runAsUser(string $command, ?string $cwd = null, ?callable $onError = null): string
    {
        return $this->getShell()->runAsUser($command, $cwd, $onError);
    }

    public function quietly(string $command, ?string $cwd = null, ?callable $onError = null): void
    {
        $this->getShell()->quietly($command, $cwd, $onError);
    }

    public function quietlyAsUser(string $command, ?string $cwd = null, ?callable $onError = null): void
    {
        $this->getShell()->quietlyAsUser($command, $cwd, $onError);
    }
}