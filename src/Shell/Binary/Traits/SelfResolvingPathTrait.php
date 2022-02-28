<?php

declare(strict_types=1);

namespace Jascha030\CLI\Shell\Binary\Traits;

use Symfony\Component\Process\Process;

trait SelfResolvingPathTrait
{
    abstract public function getName(): string;

    public function getPath(): string
    {
        $output = Process::fromShellCommandline("which {$this->getName()}")
            ->mustRun()
            ->getOutput();

        return substr($output, 0, -1);
    }
}
