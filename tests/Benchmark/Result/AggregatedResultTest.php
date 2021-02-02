<?php

namespace JMGQ\AStar\Tests\Benchmark\Result;

use JMGQ\AStar\Benchmark\Result\AggregatedResult;

class AggregatedResultTest extends \PHPUnit_Framework_TestCase
{
    public function invalidNaturalNumberProvider()
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

    public function invalidNonNegativeIntegerProvider()
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

    public function testShouldSetValidValues()
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
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid size
     */
    public function testShouldNotSetInvalidSize($invalidSize)
    {
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

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
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid average duration
     */
    public function testShouldNotSetInvalidAverageDuration($invalidAverageDuration)
    {
        $validSize = 5;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

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
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid minimum duration
     */
    public function testShouldNotSetInvalidMinimumDuration($invalidMinimumDuration)
    {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

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
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid maximum duration
     */
    public function testShouldNotSetInvalidMaximumDuration($invalidMaximumDuration)
    {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validNumberOfSolutions = 4;
        $validNumberOfTerrains = 6;

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
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid number of solutions
     */
    public function testShouldNotSetInvalidNumberOfSolutions($invalidNumberOfSolutions)
    {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfTerrains = 6;

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
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid number of terrains
     */
    public function testShouldNotSetInvalidNumberOfTerrains($invalidNumberOfTerrains)
    {
        $validSize = 5;
        $validAverageDuration = 2;
        $validMinimumDuration = 1;
        $validMaximumDuration = 3;
        $validNumberOfSolutions = 5;

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
