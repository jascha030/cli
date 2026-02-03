<?php

declare(strict_types=1);

namespace Jascha030\CLI\Tests\Shell\Binary\Traits;

use Jascha030\CLI\Shell\Binary\Traits\SelfResolvingPathTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * @internal
 */
#[CoversClass(SelfResolvingPathTrait::class)]
final class SelfResolvingPathTraitTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('php', $this->getTestMock()->getName());
    }

    public function testGetPath(): void
    {
        $path = $this->getTestMock()->getPath();

        // Get target path, if path is a symlink.
        if (is_link($path)) {
            $path = realpath($path);
        }

        $this->assertEquals((new PhpExecutableFinder())->find(), $path);
    }

    /**
     * @noinspection PhpTraitUsageOutsideUseInspection
     */
    private function getTestMock(): MockObject|SelfResolvingPathTrait
    {
        $mock = $this->getMockForTrait(SelfResolvingPathTrait::class);

        $mock->expects($this->any())
            ->method('getName')
            ->willReturn('php');

        return $mock;
    }
}
