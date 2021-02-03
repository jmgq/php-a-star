<?php

namespace JMGQ\AStar\Tests\Benchmark\Result;

use JMGQ\AStar\Benchmark\Result\Result;

class ResultTest extends \PHPUnit_Framework_TestCase
{
    public function validValuesProvider()
    {
        return array(
            array(1, PHP_INT_MAX, true),
            array('4', '2', false),
            array(20, 0, true),
        );
    }

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

    /**
     * @dataProvider validValuesProvider
     */
    public function testShouldSetValidValues($size, $duration, $hasSolution)
    {
        $expectedSize = (int) $size;
        $expectedDuration = (int) $duration;

        $sut = new Result($size, $duration, $hasSolution);

        $this->assertSame($expectedSize, $sut->getSize());
        $this->assertSame($expectedDuration, $sut->getDuration());
        $this->assertSame($hasSolution, $sut->hasSolution());
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid size
     */
    public function testShouldNotSetInvalidSize($invalidSize)
    {
        $validDuration = 200;
        $validHasSolution = true;

        new Result($invalidSize, $validDuration, $validHasSolution);
    }

    /**
     * @dataProvider invalidNonNegativeIntegerProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid duration
     */
    public function testShouldNotSetInvalidDuration($invalidDuration)
    {
        $validSize = '5';
        $validHasSolution = false;

        new Result($validSize, $invalidDuration, $validHasSolution);
    }

    public function testShouldReturnABooleanTypeWhenRetrievingHasSolution()
    {
        $size = 2;
        $duration = 3;
        $hasSolution = 1;

        $sut = new Result($size, $duration, $hasSolution);

        $this->assertTrue($sut->hasSolution());
    }
}