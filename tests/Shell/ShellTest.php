<?php

declare(strict_types=1);

namespace Jascha030\CLI\Tests\Shell;

use Jascha030\CLI\Shell\Shell;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * @internal
 */
#[CoversClass(Shell::class)]
class ShellTest extends TestCase
{
    public function testRun(): void
    {
        $this->assertEquals('test', (new Shell())->run('echo "test"'));
    }

    public function testThrowsExceptionOnNonExistingCommand(): void
    {
        $this->expectException(ProcessFailedException::class);

        (new Shell())->quietly('somethingthatbreaks');
    }
}
