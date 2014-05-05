<?php

namespace JMGQ\AStar\Tests;

class BaseAStarTest extends \PHPUnit_Framework_TestCase
{
    protected $sut;

    public function testShouldFindSolutionIfTheStartAndGoalNodesAreTheSame()
    {
        $uniqueID = 'someUniqueID';

        $startNode = $this->getMock('JMGQ\AStar\Node');
        $startNode->expects($this->any())
            ->method('getID')
            ->will($this->returnValue($uniqueID));

        $goalNode = $this->getMock('JMGQ\AStar\Node');
        $goalNode->expects($this->any())
            ->method('getID')
            ->will($this->returnValue($uniqueID));

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
            ->will($this->returnValue('startNodeID'));

        $unreachableGoalNode = $this->getMock('JMGQ\AStar\Node');
        $unreachableGoalNode->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('unreachableGoalNode'));

        $this->sut->expects($this->any())
            ->method('generateAdjacentNodes')
            ->will($this->returnValue(array()));

        $path = $this->sut->run($startNode, $unreachableGoalNode);

        $this->assertCount(0, $path);
    }

    public function testSimplePath()
    {
        $startNode = $this->getMockForAbstractClass('JMGQ\AStar\AbstractNode');
        $startNode->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('startNode'));

        $goalNode = $this->getMockForAbstractClass('JMGQ\AStar\AbstractNode');
        $goalNode->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('goalNode'));

        $otherNode = $this->getMockForAbstractClass('JMGQ\AStar\AbstractNode');
        $otherNode->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('otherNode'));

        $allNodes = array($startNode, $goalNode, $otherNode);

        $this->sut->expects($this->any())
            ->method('generateAdjacentNodes')
            ->will(
                $this->returnCallback(
                    // The adjacent nodes are all other nodes (not including itself)
                    function ($argumentNode) use ($allNodes) {
                        $adjacentNodes = array();

                        foreach ($allNodes as $node) {
                            if ($argumentNode->getID() !== $node->getID()) {
                                $adjacentNodes[] = clone $node;
                            }
                        }

                        return $adjacentNodes;
                    }
                )
            );

        $this->sut->expects($this->any())
            ->method('calculateRealDistance')
            ->will($this->returnValue(5));

        $this->sut->expects($this->any())
            ->method('calculateHeuristicDistance')
            ->will($this->returnValue(2));

        $path = $this->sut->run($startNode, $goalNode);

        $this->assertCount(2, $path);
        $this->assertSame($startNode->getID(), $path[0]->getID());
        $this->assertSame($goalNode->getID(), $path[1]->getID());
    }
}
