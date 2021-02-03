<?php

namespace JMGQ\AStar\Tests\Example\Terrain;

use JMGQ\AStar\Example\Terrain\MyNode;

class MyNodeTest extends \PHPUnit_Framework_TestCase
{
    public function validPointProvider()
    {
        return array(
            array(3, 4),
            array(0, 3),
            array('1', '2'),
            array('2', 2),
            array(0, PHP_INT_MAX)
        );
    }

    public function invalidPointProvider()
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

    public function testShouldImplementTheNodeInterface()
    {
        $sut = new MyNode(0, 0);

        $this->assertInstanceOf('JMGQ\AStar\Node', $sut);
    }

    /**
     * @dataProvider validPointProvider
     */
    public function testShouldSetValidPoint($row, $column)
    {
        $expectedRow = (int) $row;
        $expectedColumn = (int) $column;

        $sut = new MyNode($row, $column);

        $this->assertSame($expectedRow, $sut->getRow());
        $this->assertSame($expectedColumn, $sut->getColumn());
    }

    /**
     * @dataProvider invalidPointProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid non negative integer
     */
    public function testShouldNotSetInvalidPoint($row, $column)
    {
        new MyNode($row, $column);
    }

    /**
     * @dataProvider validPointProvider
     */
    public function testShouldGenerateAnID($row, $column)
    {
        $expectedID = $row . 'x' . $column;

        $sut = new MyNode($row, $column);

        $this->assertSame($expectedID, $sut->getID());
    }

    public function testShouldCreateNewInstanceFromNode()
    {
        $row = 3;
        $column = 5;
        $nodeID = $row . 'x' . $column;

        $node = $this->getMock('JMGQ\AStar\Node');
        $node->expects($this->once())
            ->method('getID')
            ->willReturn($nodeID);

        $myNode = MyNode::fromNode($node);

        $this->assertSame($row, $myNode->getRow());
        $this->assertSame($column, $myNode->getColumn());
        $this->assertSame($nodeID, $myNode->getID());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid node
     */
    public function testShouldNotCreateNewInstanceFromInvalidNode()
    {
        $nodeID = 'foo';

        $node = $this->getMock('JMGQ\AStar\Node');
        $node->expects($this->once())
            ->method('getID')
            ->willReturn($nodeID);

        MyNode::fromNode($node);
    }
}
