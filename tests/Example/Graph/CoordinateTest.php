<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\Coordinate;
use PHPUnit\Framework\TestCase;

class CoordinateTest extends TestCase
{
    /**
     * @return mixed[][]
     */
    public function validPointProvider(): array
    {
        $PHP_INT_MIN = ~PHP_INT_MAX;

        return [
            [3, 4],
            [0, 3],
            ['1', '2'],
            ['2', 2],
            [$PHP_INT_MIN, PHP_INT_MAX],
            [-1, 3],
            ['-2', -8],
            [4, -7],
        ];
    }

    /**
     * @return mixed[][]
     */
    public function invalidPointProvider(): array
    {
        return [
            [4, null],
            [null, 2],
            ['a', 2],
            [[], false],
        ];
    }

    /**
     * @dataProvider validPointProvider
     */
    public function testShouldSetValidPoint(mixed $x, mixed $y): void
    {
        $expectedX = (int) $x;
        $expectedY = (int) $y;

        $sut = new Coordinate($x, $y);

        $this->assertSame($expectedX, $sut->getX());
        $this->assertSame($expectedY, $sut->getY());
    }

    /**
     * @dataProvider invalidPointProvider
     */
    public function testShouldNotSetInvalidPoint(mixed $x, mixed $y): void
    {
        $this->expectException(\TypeError::class);

        new Coordinate($x, $y);
    }

    /**
     * @dataProvider validPointProvider
     */
    public function testShouldGenerateAnId(mixed $x, mixed $y): void
    {
        /** @psalm-suppress MixedOperand $x and $y will be an integer or a string representing an integer */
        $expectedId = $x . 'x' . $y;

        $sut = new Coordinate($x, $y);

        $this->assertSame($expectedId, $sut->getId());
    }
}
