<?php

namespace JMGQ\AStar\Tests\Benchmark\Result;

use JMGQ\AStar\Benchmark\Result\Result;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function validValuesProvider(): array
    {
        return array(
            array(1, PHP_INT_MAX, true),
            array('4', '2', false),
            array(20, 0, true),
        );
    }

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

    /**
     * @dataProvider validValuesProvider
     */
    public function testShouldSetValidValues($size, $duration, $hasSolution): void
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
     */
    public function testShouldNotSetInvalidSize($invalidSize): void
    {
        $validDuration = 200;
        $validHasSolution = true;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid size');

        new Result($invalidSize, $validDuration, $validHasSolution);
    }

    /**
     * @dataProvider invalidNonNegativeIntegerProvider
     */
    public function testShouldNotSetInvalidDuration($invalidDuration): void
    {
        $validSize = '5';
        $validHasSolution = false;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid duration');

        new Result($validSize, $invalidDuration, $validHasSolution);
    }

    public function testShouldReturnABooleanTypeWhenRetrievingHasSolution(): void
    {
        $size = 2;
        $duration = 3;
        $hasSolution = 1;

        $sut = new Result($size, $duration, $hasSolution);

        $this->assertTrue($sut->hasSolution());
    }
}
