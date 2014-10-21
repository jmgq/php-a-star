<?php

namespace JMGQ\AStar\Tests;

class AlgorithmTest extends BaseAStarTest
{
    public function setUp()
    {
        $this->sut = $this->getMockForAbstractClass('JMGQ\AStar\Algorithm');
    }

    public function testOpenListShouldBeAPriorityQueue()
    {
        $this->assertInstanceOf('JMGQ\AStar\NodePriorityQueue', $this->sut->getOpenList());
    }

    public function testClosedListShouldBeANodeList()
    {
        $this->assertInstanceOf('JMGQ\AStar\NodeList', $this->sut->getClosedList());
    }

    public function testShouldResetToCleanState()
    {
        $node = $this->getMock('JMGQ\AStar\Node');
        $node->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('someUniqueID'));

        $this->sut->getOpenList()->add($node);
        $this->sut->getClosedList()->add($node);

        $this->assertCount(1, $this->sut->getOpenList());
        $this->assertCount(1, $this->sut->getClosedList());

        $this->sut->clear();

        $this->assertCount(0, $this->sut->getOpenList());
        $this->assertCount(0, $this->sut->getClosedList());
    }
}
