<?php

declare(strict_types=1);

namespace Jascha030\CLI\Tests\Shell\Binary;

use Jascha030\CLI\Shell\Binary\BinaryAbstract;
use Jascha030\CLI\Shell\Binary\BinaryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(BinaryAbstract::class)]
class BinaryAbstractTest extends TestCase
{
    #[Depends('testConstruct')]
    public function testGetName(BinaryInterface $binary): void
    {
        $this->assertEquals('testBinary', $binary->getName());
    }

    #[Depends('testConstruct')]
    public function testGetVersion(BinaryInterface $binary): void
    {
        $this->assertEquals('1.0.0', $binary->getVersion());
    }

    #[Depends('testConstruct')]
    public function testGetPath(BinaryInterface $binary): void
    {
        $this->assertEquals(__DIR__, $binary->getPath());
    }

    public function testConstruct(): BinaryInterface
    {
        $mock = $this->getMockForAbstractClass(BinaryAbstract::class, [
            'name'    => 'testBinary',
            'path'    => __DIR__,
            'version' => '1.0.0',
        ]);

        $this->assertInstanceOf(BinaryInterface::class, $mock);

        return $mock;
    }
}
