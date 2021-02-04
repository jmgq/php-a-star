<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\MyNode;
use JMGQ\AStar\Node;
use PHPUnit\Framework\TestCase;

class MyNodeTest extends TestCase
{
    public function validPointProvider(): array
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

    public function invalidPointProvider(): array
    {
        return array(
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
    public function testShouldSetValidPoint($x, $y): void
    {
        $expectedX = (int) $x;
        $expectedY = (int) $y;

        $sut = new MyNode($x, $y);

        $this->assertSame($expectedX, $sut->getX());
        $this->assertSame($expectedY, $sut->getY());
    }

    /**
     * @dataProvider invalidPointProvider
     */
    public function testShouldNotSetInvalidPoint($x, $y): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid integer');

        new MyNode($x, $y);
    }

    /**
     * @dataProvider validPointProvider
     */
    public function testShouldGenerateAnID($x, $y): void
    {
        $expectedID = $x . 'x' . $y;

        $sut = new MyNode($x, $y);

        $this->assertSame($expectedID, $sut->getID());
    }

    public function testShouldCreateNewInstanceFromNode(): void
    {
        $x = -3;
        $y = 5;
        $nodeID = $x . 'x' . $y;

        $node = $this->createMock(Node::class);
        $node->expects($this->once())
            ->method('getID')
            ->willReturn($nodeID);

        $myNode = MyNode::fromNode($node);

        $this->assertSame($x, $myNode->getX());
        $this->assertSame($y, $myNode->getY());
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
