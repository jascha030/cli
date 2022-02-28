<?php

declare(strict_types=1);

namespace Jascha030\CLI\Shell;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use function Jascha030\CLI\Helpers\user;
use function passthru;

class Shell implements ShellInterface
{
    /**
     * {@inheritDoc}
     */
    public function run(string $command, ?string $cwd = null, ?callable $onError = null): string
    {
        return $this->runCommand($command, $cwd, $onError);
    }

    /**
     * {@inheritDoc}
     */
    public function runAsUser(string $command, ?string $cwd = null, ?callable $onError = null): string
    {
        $user = user();

        return $this->runCommand("sudo -u {$user} {$command}", $cwd, $onError);
    }

    /**
     * {@inheritDoc}
     */
    public function quietly(string $command, ?string $cwd = null, ?callable $onError = null): void
    {
        $this->runCommand("{$command} > /dev/null 2>&1", $cwd, $onError);
    }

    /**
     * {@inheritDoc}
     */
    public function quietlyAsUser(string $command, ?string $cwd = null, ?callable $onError = null): void
    {
        $this->runCommand("{$command} > /dev/null 2>&1", $cwd, $onError);
    }

    /**
     * Execute a command and write the console output to current STDOUT.
     *
     * @param null|OutputInterface $output OutputInterface to write to, if none is provided, php's `passthru()` function is used
     */
    public function passthru(string $command, ?OutputInterface $output = null, ?string $cwd = null): void
    {
        if (null !== $output) {
            passthru($command);
        }

        foreach ($this->start($command, $cwd) as $type => $line) {
            $output->writeln(
                Process::ERR === $type
                    ? sprintf('<error>%s</error>', $line)
                    : $line
            );
        }
    }

    public function runCommand(string $command, ?string $cwd = null, ?callable $onError = null): string
    {
        $onError = $onError ?: static function () {
        };

        $process = $this->create($command, $cwd)->mustRun();

        if ($process->getExitCode() > 0) {
            $onError($process->getExitCode(), $process->getOutput());
        }

        $output = $process->getOutput();

        if (str_ends_with($output, PHP_EOL)) {
            return substr($output, 0, -1);
        }

        return $output;
    }

    /**
     * Return a started Process which can be used to loop, through the output.
     */
    protected function start(string $command, ?string $cwd): Process
    {
        $process = $this->create($command, $cwd);
        $process->start();

        return $process;
    }

    /**
     * Create a Process object.
     */
    protected function create(string $command, ?string $cwd = null): Process
    {
        return Process::fromShellCommandline($command, $cwd);
    }
}
