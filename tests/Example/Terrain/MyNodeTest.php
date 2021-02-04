<?php

namespace JMGQ\AStar\Tests\Example\Terrain;

use JMGQ\AStar\Example\Terrain\MyNode;
use JMGQ\AStar\Node;
use PHPUnit\Framework\TestCase;

class MyNodeTest extends TestCase
{
    public function validPointProvider(): array
    {
        return array(
            array(3, 4),
            array(0, 3),
            array('1', '2'),
            array('2', 2),
            array(0, PHP_INT_MAX)
        );
    }

    public function invalidPointProvider(): array
    {
        return array(
            array(-1, 3),
            array(2, -8),
            array(2.3, 2),
            array(4, null),
            array(null, 2),
            array('a', 2),
            array(array(), false)
        );
    }

    public function testShouldImplementTheNodeInterface(): void
    {
        $sut = new MyNode(0, 0);

        $this->assertInstanceOf(Node::class, $sut);
    }

    /**
     * @dataProvider validPointProvider
     */
    public function testShouldSetValidPoint($row, $column): void
    {
        $expectedRow = (int) $row;
        $expectedColumn = (int) $column;

        $sut = new MyNode($row, $column);

        $this->assertSame($expectedRow, $sut->getRow());
        $this->assertSame($expectedColumn, $sut->getColumn());
    }

    /**
     * @dataProvider invalidPointProvider
     */
    public function testShouldNotSetInvalidPoint($row, $column): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid non negative integer');

        new MyNode($row, $column);
    }

    /**
     * @dataProvider validPointProvider
     */
    public function testShouldGenerateAnID($row, $column): void
    {
        $expectedID = $row . 'x' . $column;

        $sut = new MyNode($row, $column);

        $this->assertSame($expectedID, $sut->getID());
    }

    public function testShouldCreateNewInstanceFromNode(): void
    {
        $row = 3;
        $column = 5;
        $nodeID = $row . 'x' . $column;

        $node = $this->createMock(Node::class);
        $node->expects($this->once())
            ->method('getID')
            ->willReturn($nodeID);

        $myNode = MyNode::fromNode($node);

        $this->assertSame($row, $myNode->getRow());
        $this->assertSame($column, $myNode->getColumn());
        $this->assertSame($nodeID, $myNode->getID());
    }

    public function testShouldNotCreateNewInstanceFromInvalidNode(): void
    {
        $nodeID = 'foo';

        $node = $this->createMock(Node::class);
        $node->expects($this->once())
            ->method('getID')
            ->willReturn($nodeID);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid node');

        MyNode::fromNode($node);
    }
}
