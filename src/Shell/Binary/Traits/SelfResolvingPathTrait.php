<?php

declare(strict_types=1);

namespace Jascha030\CLI\Shell\Binary\Traits;

use Symfony\Component\Process\Process;

trait SelfResolvingPathTrait
{
    abstract public function getName(): string;

    public function getPath(): string
    {
        $command = "$(which {$this->getName()})";

        return Process::fromShellCommandline($command)->mustRun()->getOutput();
    }
}
