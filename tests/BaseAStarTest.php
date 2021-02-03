<?php

namespace JMGQ\AStar\Tests;

abstract class BaseAStarTest extends \PHPUnit_Framework_TestCase
{
    protected $sut;

    public function testShouldFindSolutionIfTheStartAndGoalNodesAreTheSame()
    {
        $uniqueID = 'someUniqueID';

        $startNode = $this->getMock('JMGQ\AStar\Node');
        $startNode->expects($this->any())
            ->method('getID')
            ->willReturn($uniqueID);

        $goalNode = $this->getMock('JMGQ\AStar\Node');
        $goalNode->expects($this->any())
            ->method('getID')
            ->willReturn($uniqueID);

        $path = $this->sut->run($startNode, $goalNode);

        $this->assertCount(1, $path);
        $this->assertSame($startNode->getID(), $path[0]->getID());
        $this->assertSame($goalNode->getID(), $path[0]->getID());
    }

    public function testShouldReturnEmptyPathIfSolutionNotFound()
    {
        $startNode = $this->getMock('JMGQ\AStar\Node');
        $startNode->expects($this->any())
            ->method('getID')
            ->willReturn('startNodeID');

        $unreachableGoalNode = $this->getMock('JMGQ\AStar\Node');
        $unreachableGoalNode->expects($this->any())
            ->method('getID')
            ->willReturn('unreachableGoalNode');

        $this->sut->expects($this->any())
            ->method('generateAdjacentNodes')
            ->willReturn(array());

        $path = $this->sut->run($startNode, $unreachableGoalNode);

        $this->assertCount(0, $path);
    }

    public function testSimplePath()
    {
        $startNode = $this->getMockForAbstractClass('JMGQ\AStar\AbstractNode');
        $startNode->expects($this->any())
            ->method('getID')
            ->willReturn('startNode');

        $goalNode = $this->getMockForAbstractClass('JMGQ\AStar\AbstractNode');
        $goalNode->expects($this->any())
            ->method('getID')
            ->willReturn('goalNode');

        $otherNode = $this->getMockForAbstractClass('JMGQ\AStar\AbstractNode');
        $otherNode->expects($this->any())
            ->method('getID')
            ->willReturn('otherNode');

        $allNodes = array($startNode, $goalNode, $otherNode);

        $this->sut->expects($this->any())
            ->method('generateAdjacentNodes')
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

        $this->sut->expects($this->any())
            ->method('calculateRealCost')
            ->willReturn(5);

        $this->sut->expects($this->any())
            ->method('calculateEstimatedCost')
            ->willReturn(2);

        $path = $this->sut->run($startNode, $goalNode);

        $this->assertCount(2, $path);
        $this->assertSame($startNode->getID(), $path[0]->getID());
        $this->assertSame($goalNode->getID(), $path[1]->getID());
    }
}
