<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\TerrainGenerator;
use PHPUnit\Framework\TestCase;

class TerrainGeneratorTest extends TestCase
{
    private TerrainGenerator $sut;

    public function invalidNaturalNumberProvider(): array
    {
        return array(
            array(0),
            array(-1),
            array(2.5),
            array(null),
            array(array()),
            array('foo'),
        );
    }

    public function invalidOptionalIntegerProvider(): array
    {
        return array(
            array('a'),
            array(array()),
            array(false),
            array(1.5),
            array(-1.5),
            array(''),
            array(' '),
        );
    }

    protected function setUp(): void
    {
        $this->sut = new TerrainGenerator();
    }

    public function testShouldGenerateTerrain(): void
    {
        $rows = 3;
        $columns = 5;

        $result = $this->sut->generate($rows, $columns);

        $this->assertSame($rows, $result->getTotalRows());
        $this->assertSame($columns, $result->getTotalColumns());
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     */
    public function testShouldNotGenerateWithInvalidRows($invalidRows): void
    {
        $columns = 5;

        $this->expectException(\InvalidArgumentException::class);

        $this->sut->generate($invalidRows, $columns);
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     */
    public function testShouldNotGenerateWithInvalidColumns($invalidColumns): void
    {
        $rows = 3;

        $this->expectException(\InvalidArgumentException::class);

        $this->sut->generate($rows, $invalidColumns);
    }

    /**
     * @dataProvider invalidOptionalIntegerProvider
     */
    public function testShouldNotGenerateWithInvalidSeed($invalidSeed): void
    {
        $rows = 3;
        $columns = 5;

        $this->expectException(\InvalidArgumentException::class);

        $this->sut->generate($rows, $columns, $invalidSeed);
    }
}
