<?php

declare(strict_types=1);

namespace Jascha030\CLI\Tests\Shell\Binary\Traits;

use Jascha030\CLI\Shell\Binary\BinaryAbstract;
use Jascha030\CLI\Shell\Binary\Traits\SelfResolvingVersionTrait;
use Jascha030\CLI\Shell\Binary\Traits\ShellDecoratorTrait;
use Jascha030\CLI\Shell\Shell;
use Jascha030\CLI\Shell\ShellInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpExecutableFinder;

use const PHP_VERSION;

/**
 * @internal
 */
#[CoversClass(SelfResolvingVersionTrait::class)]
final class SelfResolvingVersionTraitTest extends TestCase
{
    public function testGetVersion(): void
    {
        $mock = $this->getTestMock();

        $this->assertEquals(PHP_VERSION, $mock->getVersion());
    }

    public function testGetVersionCommand(): void
    {
        $mock = $this->getTestMock();

        $this->assertEquals('-v', $mock->getVersionCommand());
    }

    public function testGetVersionRegex(): void
    {
        $mock = $this->getTestMock();

        preg_match($mock->getVersionRegex(), 'abcdef 1.0.0', $matches);

        $this->assertEquals('1.0.0', $matches[0]);
    }

    public function testGetVersionWithoutRegex(): void
    {
        $this->assertStringStartsWith('PHP', $this->getPrivateClassMock()->getVersion());
    }

    /**
     * @noinspection PhpTraitUsageOutsideUseInspection
     */
    private function getTestMock(): MockObject|SelfResolvingVersionTrait
    {
        $phpBinaryPath = (new PhpExecutableFinder())->find();

        $traitMock = $this->getMockForTrait(SelfResolvingVersionTrait::class);

        $traitMock->expects($this->any())
            ->method('getPath')
            ->willReturn($phpBinaryPath);

        return $traitMock;
    }

    private function getPrivateClassMock(): BinaryAbstract
    {
        $phpPath = (new PhpExecutableFinder())->find();

        return new class ('php', $phpPath, null) extends BinaryAbstract {
            use SelfResolvingVersionTrait;
            use ShellDecoratorTrait;

            public function getShell(): ShellInterface
            {
                return new Shell();
            }

            public function getVersionRegex(): ?string
            {
                return null;
            }
        };
    }
}
