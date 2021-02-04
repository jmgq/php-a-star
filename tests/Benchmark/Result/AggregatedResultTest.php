<?php

namespace JMGQ\AStar\Tests\Benchmark\Result;

use JMGQ\AStar\Benchmark\Result\AggregatedResult;
use PHPUnit\Framework\TestCase;

class AggregatedResultTest extends TestCase
{
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

    public function invalidNonNegativeIntegerProvider(): array
    {
        return array(
            array(-1),
            array(-0.5),
            array(1.5),
            array(null),
            array('a'),
            array(array()),
            array(false),
        );
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
     */
    public function testShouldNotSetInvalidSize($invalidSize): void
    {
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid size');

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
     */
    public function testShouldNotSetInvalidAverageDuration($invalidAverageDuration): void
    {
        $validSize = 5;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid average duration');

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
     */
    public function testShouldNotSetInvalidMinimumDuration($invalidMinimumDuration): void
    {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid minimum duration');

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
     */
    public function testShouldNotSetInvalidMaximumDuration($invalidMaximumDuration): void
    {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid maximum duration');

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
     */
    public function testShouldNotSetInvalidNumberOfSolutions($invalidNumberOfSolutions): void
    {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfTerrains = 6;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid number of solutions');

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
     */
    public function testShouldNotSetInvalidNumberOfTerrains($invalidNumberOfTerrains): void
    {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 5;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid number of terrains');

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
