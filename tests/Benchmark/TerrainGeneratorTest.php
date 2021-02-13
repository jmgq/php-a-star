<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\TerrainGenerator;
use PHPUnit\Framework\TestCase;

class TerrainGeneratorTest extends TestCase
{
    private TerrainGenerator $sut;

    /**
     * @return mixed[][]
     */
    public function invalidNaturalNumberProvider(): array
    {
        return [
            [0, \InvalidArgumentException::class],
            [-1, \InvalidArgumentException::class],
            [null, \TypeError::class],
            [[], \TypeError::class],
            ['foo', \TypeError::class],
        ];
    }

    /**
     * @return mixed[][]
     */
    public function invalidOptionalIntegerProvider(): array
    {
        return [
            ['a', \TypeError::class],
            [[], \TypeError::class],
            ['', \TypeError::class],
            [' ', \TypeError::class],
        ];
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
     * @param mixed $invalidRows
     * @param class-string<\Throwable> $expectedException
     */
    public function testShouldNotGenerateWithInvalidRows(mixed $invalidRows, string $expectedException): void
    {
        $columns = 5;

        $this->expectException($expectedException);

        $this->sut->generate($invalidRows, $columns);
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     * @param mixed $invalidColumns
     * @param class-string<\Throwable> $expectedException
     */
    public function testShouldNotGenerateWithInvalidColumns(mixed $invalidColumns, string $expectedException): void
    {
        $rows = 3;

        $this->expectException($expectedException);

        $this->sut->generate($rows, $invalidColumns);
    }

    /**
     * @dataProvider invalidOptionalIntegerProvider
     * @param mixed $invalidSeed
     * @param class-string<\Throwable> $expectedException
     */
    public function testShouldNotGenerateWithInvalidSeed(mixed $invalidSeed, string $expectedException): void
    {
        $rows = 3;
        $columns = 5;

        $this->expectException($expectedException);

        $this->sut->generate($rows, $columns, $invalidSeed);
    }
}
