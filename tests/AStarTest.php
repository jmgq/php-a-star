<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\AStar;
use JMGQ\AStar\DomainLogicInterface;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

class AStarTest extends TestCase
{
    /** @var AStar<string> */
    private AStar $sut;
    /** @var Stub & DomainLogicInterface<string> */
    private Stub | DomainLogicInterface $domainLogic;

    protected function setUp(): void
    {
        $this->domainLogic = $this->createStub(DomainLogicInterface::class);

        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->sut = new AStar($this->domainLogic);
    }

    public function testShouldFindSolutionIfTheStartAndGoalNodesAreTheSame(): void
    {
        $startNode = 'foo';
        $goalNode = 'foo';

        $path = (array) $this->sut->run($startNode, $goalNode);

        $this->assertCount(1, $path);

        $firstAndOnlySolutionNode = reset($path);

        $this->assertSame($startNode, $firstAndOnlySolutionNode);
        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertSame($goalNode, $firstAndOnlySolutionNode);
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
            ->willReturnCallback(function (string $argumentNode) use ($allNodes) {
                // The adjacent nodes are all other nodes (not including itself)
                return array_filter($allNodes, static fn ($node) => $argumentNode !== $node);
            });

        $this->domainLogic->method('calculateRealCost')
            ->willReturn(5);

        $this->domainLogic->method('calculateEstimatedCost')
            ->willReturn(2);

        $path = (array) $this->sut->run($startNode, $goalNode);

        $this->assertCount(2, $path);

        $firstSolutionNode = reset($path);
        $lastSolutionNode = end($path);

        $this->assertSame($startNode, $firstSolutionNode);
        $this->assertSame($goalNode, $lastSolutionNode);
    }
}
