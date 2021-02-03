<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\MyNode;

class MyNodeTest extends \PHPUnit_Framework_TestCase
{
    public function validPointProvider()
    {
        $PHP_INT_MIN = ~PHP_INT_MAX;

        return array(
            array(3, 4),
            array(0, 3),
            array('1', '2'),
            array('2', 2),
            array($PHP_INT_MIN, PHP_INT_MAX),
            array(-1, 3),
            array('-2', -8),
            array(4, -7)
        );
    }

    public function invalidPointProvider()
    {
        return array(
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
    public function testShouldSetValidPoint($x, $y)
    {
        $expectedX = (int) $x;
        $expectedY = (int) $y;

        $sut = new MyNode($x, $y);

        $this->assertSame($expectedX, $sut->getX());
        $this->assertSame($expectedY, $sut->getY());
    }

    /**
     * @dataProvider invalidPointProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid integer
     */
    public function testShouldNotSetInvalidPoint($x, $y)
    {
        new MyNode($x, $y);
    }

    /**
     * @dataProvider validPointProvider
     */
    public function testShouldGenerateAnID($x, $y)
    {
        $expectedID = $x . 'x' . $y;

        $sut = new MyNode($x, $y);

        $this->assertSame($expectedID, $sut->getID());
    }

    public function testShouldCreateNewInstanceFromNode()
    {
        $x = -3;
        $y = 5;
        $nodeID = $x . 'x' . $y;

        $node = $this->getMock('JMGQ\AStar\Node');
        $node->expects($this->once())
            ->method('getID')
            ->willReturn($nodeID);

        $myNode = MyNode::fromNode($node);

        $this->assertSame($x, $myNode->getX());
        $this->assertSame($y, $myNode->getY());
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
