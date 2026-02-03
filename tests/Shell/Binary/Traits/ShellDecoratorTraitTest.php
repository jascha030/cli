<?php

declare(strict_types=1);

namespace Jascha030\CLI\Tests\Shell\Binary\Traits;

use Jascha030\CLI\Shell\Binary\Traits\ShellDecoratorTrait;
use Jascha030\CLI\Shell\Shell;
use Jascha030\CLI\Shell\ShellInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * @internal
 */
#[CoversClass(ShellDecoratorTrait::class)]
class ShellDecoratorTraitTest extends TestCase
{
    public function testRun(): void
    {
        $this->assertEquals(
            (new Shell())->run('`which php` -r "echo \"test\";"'),
            $this->getTestMock()->run(' -r "echo \"test\";"')
        );
    }

    public function testGetShell(): void
    {
        $this->assertInstanceOf(ShellInterface::class, $this->getTestMock()->getShell());
    }

    /**
     * @noinspection PhpTraitUsageOutsideUseInspection
     */
    private function getTestMock(): ShellDecoratorTrait|MockObject
    {
        $phpPath = (new PhpExecutableFinder())->find();
        $shell   = new Shell();

        $mock = $this->getMockForTrait(ShellDecoratorTrait::class);

        $mock->expects($this->any())
            ->method('getShell')
            ->willReturn($shell);

        $mock->expects($this->any())
            ->method('getPath')
            ->willReturn($phpPath);

        return $mock;
    }
}
