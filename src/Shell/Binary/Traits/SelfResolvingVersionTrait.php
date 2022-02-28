<?php

declare(strict_types=1);

namespace Jascha030\CLI\Shell\Binary\Traits;

use Symfony\Component\Process\Process;

trait SelfResolvingVersionTrait
{
    abstract public function getPath(): string;

    public function getVersion(): ?string
    {
        $command = "{$this->getPath()} {$this->getVersionCommand()}";
        $process = Process::fromShellCommandline($command);

        if (! $this->getVersionRegex()) {
            return $process->mustRun()->getOutput();
        }

        $match = preg_match($this->getVersionRegex(), $process->mustRun()->getOutput(), $matches);

        return $match > 0
            ? $matches[0]
            : $process->mustRun()->getOutput();
    }

    public function getVersionRegex(): ?string
    {
        return '/\\d{1,2}\\.\\d{1,2}\\.\\d{1,2}/';
    }

    public function getVersionCommand(): string
    {
        return '-v';
    }
}
