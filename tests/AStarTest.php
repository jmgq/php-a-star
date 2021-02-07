<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\AStar;
use JMGQ\AStar\DomainLogicInterface;
use JMGQ\AStar\NodeCollectionInterface;
use PHPUnit\Framework\TestCase;

class AStarTest extends TestCase
{
    private AStar $sut;
    private DomainLogicInterface $domainLogic;

    protected function setUp(): void
    {
        $this->domainLogic = $this->createStub(DomainLogicInterface::class);

        $this->sut = new AStar($this->domainLogic);
    }

    public function testShouldFindSolutionIfTheStartAndGoalNodesAreTheSame(): void
    {
        $startNode = 'foo';
        $goalNode = 'foo';

        $path = $this->sut->run($startNode, $goalNode);

        $this->assertCount(1, $path);
        $this->assertSame($startNode, $path[0]);
        $this->assertSame($goalNode, $path[0]);
    }

    public function testShouldReturnEmptyPathIfSolutionNotFound(): void
    {
        $startNode = 'startNode';
        $unreachableGoalNode = 'unreachableGoalNode';

        $this->domainLogic->method('getAdjacentNodes')
            ->willReturn([]);

        $path = $this->sut->run($startNode, $unreachableGoalNode);

        $this->assertCount(0, $path);
    }

    public function testSimplePath(): void
    {
        $startNode = 'startNode';
        $goalNode = 'goalNode';
        $otherNode = 'otherNode';

        $allNodes = [$startNode, $goalNode, $otherNode];

        $this->domainLogic->method('getAdjacentNodes')
            ->willReturnCallback(function ($argumentNode) use ($allNodes) {
                // The adjacent nodes are all other nodes (not including itself)
                return array_filter($allNodes, static fn ($node) => $argumentNode !== $node);
            });

        $this->domainLogic->method('calculateRealCost')
            ->willReturn(5);

        $this->domainLogic->method('calculateEstimatedCost')
            ->willReturn(2);

        $path = $this->sut->run($startNode, $goalNode);

        $this->assertCount(2, $path);
        $this->assertSame($startNode, $path[0]);
        $this->assertSame($goalNode, $path[1]);
    }

    public function testShouldResetToCleanStateInEveryExecution(): void
    {
        $openList = $this->createMock(NodeCollectionInterface::class);
        $openList->method('isEmpty')
            ->willReturn(true);
        $openList->expects($this->exactly(3))
            ->method('clear');

        $closedList = $this->createMock(NodeCollectionInterface::class);
        $closedList->expects($this->exactly(3))
            ->method('clear');

        $this->sut = new AStar($this->domainLogic, $openList, $closedList);

        $node = 'foo';

        $this->sut->run($node, $node);
        $this->sut->run($node, $node);
        $this->sut->run($node, $node);
    }
}
