<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\Coordinate;
use JMGQ\AStar\Example\Graph\Link;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    /**
     * @return mixed[][]
     */
    public function validDistanceProvider(): array
    {
        return [
            [0],
            [3],
            ['7'],
            [99999999],
            [7.5],
            ['12.345'],
        ];
    }

    /**
     * @return mixed[][]
     */
    public function invalidDistanceProvider(): array
    {
        return [
            [-1, \InvalidArgumentException::class, 'Invalid distance'],
            [-0.5, \InvalidArgumentException::class, 'Invalid distance'],
            [null, \TypeError::class, 'must be of type float'],
            ['a', \TypeError::class, 'must be of type float'],
            [[], \TypeError::class, 'must be of type float'],
        ];
    }

    /**
     * @dataProvider validDistanceProvider
     */
    public function testShouldSetValidDistance(mixed $distance): void
    {
        $expectedDistance = (float) $distance;

        $source = $this->createStub(Coordinate::class);
        $destination = $this->createStub(Coordinate::class);

        $sut = new Link($source, $destination, $distance);

        $this->assertSame($source, $sut->getSource());
        $this->assertSame($destination, $sut->getDestination());
        $this->assertSame($expectedDistance, $sut->getDistance());
    }

    /**
     * @dataProvider invalidDistanceProvider
     * @param mixed $distance
     * @param class-string<\Throwable> $expectedException
     * @param string $expectedExceptionMessage
     */
    public function testShouldNotSetInvalidDistance(
        mixed $distance,
        string $expectedException,
        string $expectedExceptionMessage
    ): void {
        $source = $this->createStub(Coordinate::class);
        $destination = $this->createStub(Coordinate::class);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);

        new Link($source, $destination, $distance);
    }
}
