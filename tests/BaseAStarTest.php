<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\AbstractNode;
use JMGQ\AStar\Algorithm;
use JMGQ\AStar\AStar;
use JMGQ\AStar\Node;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

abstract class BaseAStarTest extends TestCase
{
    protected Algorithm|AStar|Stub $sut;

    public function testShouldFindSolutionIfTheStartAndGoalNodesAreTheSame(): void
    {
        $uniqueID = 'someUniqueID';

        $startNode = $this->createStub(Node::class);
        $startNode->method('getID')
            ->willReturn($uniqueID);

        $goalNode = $this->createStub(Node::class);
        $goalNode->method('getID')
            ->willReturn($uniqueID);

        $path = $this->sut->run($startNode, $goalNode);

        $this->assertCount(1, $path);
        $this->assertSame($startNode->getID(), $path[0]->getID());
        $this->assertSame($goalNode->getID(), $path[0]->getID());
    }

    public function testShouldReturnEmptyPathIfSolutionNotFound(): void
    {
        $startNode = $this->createStub(Node::class);
        $startNode->method('getID')
            ->willReturn('startNodeID');

        $unreachableGoalNode = $this->createStub(Node::class);
        $unreachableGoalNode->method('getID')
            ->willReturn('unreachableGoalNode');

        $this->sut->method('generateAdjacentNodes')
            ->willReturn(array());

        $path = $this->sut->run($startNode, $unreachableGoalNode);

        $this->assertCount(0, $path);
    }

    public function testSimplePath(): void
    {
        $startNode = $this->getMockForAbstractClass(AbstractNode::class);
        $startNode->method('getID')
            ->willReturn('startNode');

        $goalNode = $this->getMockForAbstractClass(AbstractNode::class);
        $goalNode->method('getID')
            ->willReturn('goalNode');

        $otherNode = $this->getMockForAbstractClass(AbstractNode::class);
        $otherNode->method('getID')
            ->willReturn('otherNode');

        $allNodes = array($startNode, $goalNode, $otherNode);

        $this->sut->method('generateAdjacentNodes')
            ->willReturnCallback(function ($argumentNode) use ($allNodes) {
                // The adjacent nodes are all other nodes (not including itself)
                $adjacentNodes = array();

                foreach ($allNodes as $node) {
                    if ($argumentNode->getID() !== $node->getID()) {
                        $adjacentNodes[] = clone $node;
                    }
                }

                return $adjacentNodes;
            });

        $this->sut->method('calculateRealCost')
            ->willReturn(5);

        $this->sut->method('calculateEstimatedCost')
            ->willReturn(2);

        $path = $this->sut->run($startNode, $goalNode);

        $this->assertCount(2, $path);
        $this->assertSame($startNode->getID(), $path[0]->getID());
        $this->assertSame($goalNode->getID(), $path[1]->getID());
    }
}
