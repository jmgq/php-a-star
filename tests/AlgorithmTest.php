<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\Algorithm;
use JMGQ\AStar\Node;
use JMGQ\AStar\NodeList;

class AlgorithmTest extends BaseAStarTest
{
    protected function setUp(): void
    {
        $this->sut = $this->getMockForAbstractClass(Algorithm::class);
    }

    public function testOpenListShouldBeANodeList(): void
    {
        $this->assertInstanceOf(NodeList::class, $this->sut->getOpenList());
    }

    public function testClosedListShouldBeANodeList(): void
    {
        $this->assertInstanceOf(NodeList::class, $this->sut->getClosedList());
    }

    public function testShouldResetToCleanState(): void
    {
        $node = $this->createStub(Node::class);
        $node->method('getID')
            ->willReturn('someUniqueID');

        $this->sut->getOpenList()->add($node);
        $this->sut->getClosedList()->add($node);

        $this->assertCount(1, $this->sut->getOpenList());
        $this->assertCount(1, $this->sut->getClosedList());

        $this->sut->clear();

        $this->assertCount(0, $this->sut->getOpenList());
        $this->assertCount(0, $this->sut->getClosedList());
    }
}
