<?php

namespace JMGQ\AStar\Tests\Benchmark\Result;

use JMGQ\AStar\Benchmark\Result\AggregatedResult;
use PHPUnit\Framework\TestCase;

class AggregatedResultTest extends TestCase
{
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
    public function invalidNonNegativeIntegerProvider(): array
    {
        return [
            [-1, \InvalidArgumentException::class],
            [null, \TypeError::class],
            ['a', \TypeError::class],
            [[], \TypeError::class],
        ];
    }

    public function testShouldSetValidValues(): void
    {
        $size = 5;
        $averageDuration = 2;
        $minimumDuration = 1;
        $maximumDuration = 3;
        $numberOfSolutions = 4;
        $numberOfTerrains = 6;

        $sut = new AggregatedResult(
            $size,
            $averageDuration,
            $minimumDuration,
            $maximumDuration,
            $numberOfSolutions,
            $numberOfTerrains
        );

        $this->assertSame($size, $sut->getSize());
        $this->assertSame($averageDuration, $sut->getAverageDuration());
        $this->assertSame($minimumDuration, $sut->getMinimumDuration());
        $this->assertSame($maximumDuration, $sut->getMaximumDuration());
        $this->assertSame($numberOfSolutions, $sut->getNumberOfSolutions());
        $this->assertSame($numberOfTerrains, $sut->getNumberOfTerrains());
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     * @param mixed $invalidSize
     * @param class-string<\Throwable> $expectedException
     */
    public function testShouldNotSetInvalidSize(mixed $invalidSize, string $expectedException): void
    {
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

        $this->expectException($expectedException);

        new AggregatedResult(
            $invalidSize,
            $validAverageDuration,
            $validMinimumDuration,
            $validMaximumDuration,
            $validNumberOfSolutions,
            $validNumberOfTerrains
        );
    }

    /**
     * @dataProvider invalidNonNegativeIntegerProvider
     * @param mixed $invalidAverageDuration
     * @param class-string<\Throwable> $expectedException
     */
    public function testShouldNotSetInvalidAverageDuration(
        mixed $invalidAverageDuration,
        string $expectedException
    ): void {
        $validSize = 5;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

        $this->expectException($expectedException);

        new AggregatedResult(
            $validSize,
            $invalidAverageDuration,
            $validMinimumDuration,
            $validMaximumDuration,
            $validNumberOfSolutions,
            $validNumberOfTerrains
        );
    }

    /**
     * @dataProvider invalidNonNegativeIntegerProvider
     * @param mixed $invalidMinimumDuration
     * @param class-string<\Throwable> $expectedException
     */
    public function testShouldNotSetInvalidMinimumDuration(
        mixed $invalidMinimumDuration,
        string $expectedException
    ): void {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

        $this->expectException($expectedException);

        new AggregatedResult(
            $validSize,
            $validAverageDuration,
            $invalidMinimumDuration,
            $validMaximumDuration,
            $validNumberOfSolutions,
            $validNumberOfTerrains
        );
    }

    /**
     * @dataProvider invalidNonNegativeIntegerProvider
     * @param mixed $invalidMaximumDuration
     * @param class-string<\Throwable> $expectedException
     */
    public function testShouldNotSetInvalidMaximumDuration(
        mixed $invalidMaximumDuration,
        string $expectedException
    ): void {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

        $this->expectException($expectedException);

        new AggregatedResult(
            $validSize,
            $validAverageDuration,
            $validMinimumDuration,
            $invalidMaximumDuration,
            $validNumberOfSolutions,
            $validNumberOfTerrains
        );
    }

    /**
     * @dataProvider invalidNonNegativeIntegerProvider
     * @param mixed $invalidNumberOfSolutions
     * @param class-string<\Throwable> $expectedException
     */
    public function testShouldNotSetInvalidNumberOfSolutions(
        mixed $invalidNumberOfSolutions,
        string $expectedException
    ): void {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfTerrains = 6;

        $this->expectException($expectedException);

        new AggregatedResult(
            $validSize,
            $validAverageDuration,
            $validMinimumDuration,
            $validMaximumDuration,
            $invalidNumberOfSolutions,
            $validNumberOfTerrains
        );
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     * @param mixed $invalidNumberOfTerrains
     * @param class-string<\Throwable> $expectedException
     */
    public function testShouldNotSetInvalidNumberOfTerrains(
        mixed $invalidNumberOfTerrains,
        string $expectedException
    ): void {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 5;

        $this->expectException($expectedException);

        new AggregatedResult(
            $validSize,
            $validAverageDuration,
            $validMinimumDuration,
            $validMaximumDuration,
            $validNumberOfSolutions,
            $invalidNumberOfTerrains
        );
    }
}
