<?php

namespace JMGQ\AStar\Tests\Example\Terrain;

use JMGQ\AStar\Example\Terrain\Position;
use JMGQ\AStar\Node\NodeIdentifierInterface;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    /**
     * @return mixed[][]
     */
    public function validPointProvider(): array
    {
        return [
            [3, 4],
            [0, 3],
            ['1', '2'],
            ['2', 2],
            [0, PHP_INT_MAX],
        ];
    }

    /**
     * @return mixed[][]
     */
    public function invalidPointProvider(): array
    {
        return [
            [-1, 3, \InvalidArgumentException::class, 'Invalid non negative integer'],
            [2, -8, \InvalidArgumentException::class, 'Invalid non negative integer'],
            [4, null, \TypeError::class, 'must be of type int'],
            [null, 2, \TypeError::class, 'must be of type int'],
            ['a', 2, \TypeError::class, 'must be of type int'],
            [[], false, \TypeError::class, 'must be of type int'],
        ];
    }

    /**
     * @return Position[][]
     */
    public function adjacentPointProvider(): array
    {
        return [
            [new Position(0, 0), new Position(0, 1)],
            [new Position(0, 0), new Position(1, 0)],
            [new Position(0, 0), new Position(1, 1)],
            [new Position(2, 2), new Position(1, 1)],
            [new Position(2, 2), new Position(1, 2)],
            [new Position(2, 2), new Position(1, 3)],
            [new Position(2, 2), new Position(2, 1)],
            [new Position(2, 2), new Position(2, 3)],
            [new Position(2, 2), new Position(3, 1)],
            [new Position(2, 2), new Position(3, 2)],
            [new Position(2, 2), new Position(3, 3)],
        ];
    }

    /**
     * @return Position[][]
     */
    public function nonAdjacentPointProvider(): array
    {
        return [
            [new Position(0, 0), new Position(0, 2)],
            [new Position(0, 0), new Position(2, 0)],
            [new Position(1, 1), new Position(3, 3)],
            [new Position(1, 1), new Position(3, 1)],
            [new Position(1, 1), new Position(1, 3)],
        ];
    }

    /**
     * @dataProvider validPointProvider
     */
    public function testShouldSetValidPoint(mixed $row, mixed $column): void
    {
        $expectedRow = (int) $row;
        $expectedColumn = (int) $column;

        $sut = new Position($row, $column);

        $this->assertSame($expectedRow, $sut->getRow());
        $this->assertSame($expectedColumn, $sut->getColumn());
    }

    /**
     * @dataProvider invalidPointProvider
     * @param mixed $row
     * @param mixed $column
     * @param class-string<\Throwable> $expectedException
     * @param string $expectedExceptionMessage
     */
    public function testShouldNotSetInvalidPoint(
        mixed $row,
        mixed $column,
        string $expectedException,
        string $expectedExceptionMessage
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);

        new Position($row, $column);
    }

    public function testShouldBeEqualToAnotherPositionWhenTheirRowsAndColumnsAreTheSame(): void
    {
        $position = new Position(1, 2);
        $samePosition = new Position(1, 2);

        $this->assertTrue($position->isEqualTo($samePosition));
        $this->assertTrue($samePosition->isEqualTo($position));
    }

    public function testShouldBeDifferentToAnotherPositionWhenTheirRowsAreDifferent(): void
    {
        $sameColumn = 5;

        $position = new Position(3, $sameColumn);
        $differentPosition = new Position(4, $sameColumn);

        $this->assertFalse($position->isEqualTo($differentPosition));
        $this->assertFalse($differentPosition->isEqualTo($position));
    }

    public function testShouldBeDifferentToAnotherPositionWhenTheirColumnsAreDifferent(): void
    {
        $sameRow = 8;

        $position = new Position($sameRow, 6);
        $differentPosition = new Position($sameRow, 7);

        $this->assertFalse($position->isEqualTo($differentPosition));
        $this->assertFalse($differentPosition->isEqualTo($position));
    }

    /**
     * @dataProvider adjacentPointProvider
     */
    public function testShouldIdentifyAdjacentPositions(Position $position, Position $adjacent): void
    {
        $this->assertTrue($position->isAdjacentTo($adjacent));
        $this->assertTrue($adjacent->isAdjacentTo($position));
    }

    /**
     * @dataProvider nonAdjacentPointProvider
     */
    public function testShouldIdentifyNonAdjacentPositions(Position $position, Position $nonAdjacent): void
    {
        $this->assertFalse($position->isAdjacentTo($nonAdjacent));
        $this->assertFalse($nonAdjacent->isAdjacentTo($position));
    }

    public function testShouldImplementTheNodeIdentifierInterface(): void
    {
        $this->assertInstanceOf(NodeIdentifierInterface::class, new Position(0, 0));
    }

    public function testShouldSetItsUniqueNodeIdBasedOnItsRowAndColumn(): void
    {
        $row = 5;
        $column = 8;
        $expectedNodeId = '5x8';

        $sut = new Position($row, $column);

        $this->assertSame($expectedNodeId, $sut->getUniqueNodeId());
    }
}
