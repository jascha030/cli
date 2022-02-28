<?php

declare(strict_types=1);

namespace Jascha030\CLI\Helpers;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Get user, or sudo user when available.
 */
function user(): ?string
{
    return $_SERVER['SUDO_USER'] ?? $_SERVER['USER'];
}

/**
 * Output a message to the console.
 */
function output(string $message, ?OutputInterface $output = null): void
{
    ($output ?? new ConsoleOutput())->writeln($message);
}

/**
 * Output an error to the console.
 */
function error(string $message, ?OutputInterface $output = null): void
{
    output("<error>{$message}</error>", $output);
}

/**
 * Output an info message to the console.
 */
function info(string $message, ?OutputInterface $output = null): void
{
    output("<info>{$message}</info>", $output);
}

/**
 * Output a multiline message to the console.
 */
function multilineOutput(array $message, ?OutputInterface $output = null): void
{
    foreach ($message as $line) {
        output($line, $output);
    }
}

/**
 * Output a multiline error to the console.
 */
function multilineError(array $message, ?OutputInterface $output = null): void
{
    foreach ($message as $line) {
        error($line, $output);
    }
}

/**
 * Output a multiline info message to the console.
 */
function multilineInfo(array $message, ?OutputInterface $output = null): void
{
    foreach ($message as $line) {
        info($line, $output);
    }
}
