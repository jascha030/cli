<?php

declare(strict_types=1);

namespace Jascha030\CLI\Shell\Binary\Traits;

use Jascha030\CLI\Shell\Binary\BinaryInterface;
use Jascha030\CLI\Shell\ShellInterface;

trait ShellDecoratorTrait
{
    /**
     * Should return the proxy ShellInterface implementation.
     *
     * @see ShellInterface
     */
    abstract public function getShell(): ShellInterface;

    /**
     * @see BinaryInterface::getPath()
     */
    abstract public function getPath(): string;

    /**
     * @see ShellInterface::run()
     */
    public function run(string $command, ?string $cwd = null, ?callable $onError = null): string
    {
        return $this->getShell()->run("{$this->getPath()} {$command}", $cwd, $onError);
    }

    /**
     * @see ShellInterface::runAsUser()
     */
    public function runAsUser(string $command, ?string $cwd = null, ?callable $onError = null): string
    {
        return $this->getShell()->runAsUser("{$this->getPath()} {$command}", $cwd, $onError);
    }

    /**
     * @see ShellInterface::quietly()
     */
    public function quietly(string $command, ?string $cwd = null, ?callable $onError = null): void
    {
        $this->getShell()->quietly("{$this->getPath()} {$command}", $cwd, $onError);
    }

    /**
     * @see ShellInterface::quietlyAsUser()
     */
    public function quietlyAsUser(string $command, ?string $cwd = null, ?callable $onError = null): void
    {
        $this->getShell()->quietlyAsUser("{$this->getPath()} {$command}", $cwd, $onError);
    }
}