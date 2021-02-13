<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\AStar;
use JMGQ\AStar\Example\Graph\Coordinate;
use JMGQ\AStar\Example\Graph\DomainLogic;
use JMGQ\AStar\Example\Graph\Graph;
use JMGQ\AStar\Example\Graph\Link;
use PHPUnit\Framework\TestCase;

class DomainLogicTest extends TestCase
{
    private DomainLogic $sut;

    protected function setUp(): void
    {
        $links = [
            new Link(new Coordinate(0, 0), new Coordinate(2, 5), 6.5),
            new Link(new Coordinate(0, 0), new Coordinate(6, 4), 23.75),
            new Link(new Coordinate(2, 5), new Coordinate(3, 3), 5),
            new Link(new Coordinate(3, 3), new Coordinate(6, 4), 3.2),
            new Link(new Coordinate(6, 4), new Coordinate(10, 10), 8),
        ];

        $graph = new Graph($links);

        $this->sut = new DomainLogic($graph);
    }

    public function testShouldGenerateAdjacentNodes(): void
    {
        $node = new Coordinate(0, 0);
        $expectedAdjacentNodes = [
            new Coordinate(2, 5),
            new Coordinate(6, 4)
        ];

        $adjacentNodes = $this->sut->getAdjacentNodes($node);

        $this->assertCount(count($expectedAdjacentNodes), $adjacentNodes);

        foreach ($expectedAdjacentNodes as $expectedNode) {
            $this->assertContainsCoordinate($expectedNode, $adjacentNodes);
        }
    }

    public function testShouldCalculateRealCost(): void
    {
        $expectedCost = 3.2;

        $node = new Coordinate(3, 3);
        $adjacentNode = new Coordinate(6, 4);

        $cost = $this->sut->calculateRealCost($node, $adjacentNode);

        $this->assertSame($expectedCost, $cost);
    }

    public function testShouldNotCalculateTheRealCostBetweenTwoUnlinkedNodes(): void
    {
        $node = new Coordinate(6, 4);
        $nonAdjacentNode = new Coordinate(3, 3);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('not linked');

        $this->sut->calculateRealCost($node, $nonAdjacentNode);
    }

    public function testShouldCalculateEstimatedCost(): void
    {
        $expectedCost = 20.2237484162;
        $maximumImprecisionAllowed = 0.0001;

        $startNode = new Coordinate(-5, 6);
        $destinationNode = new Coordinate(15, 9);

        $cost = $this->sut->calculateEstimatedCost($startNode, $destinationNode);

        $this->assertEqualsWithDelta($expectedCost, $cost, $maximumImprecisionAllowed);
    }

    public function testShouldGetRightSolution(): void
    {
        $start = new Coordinate(0, 0);
        $goal = new Coordinate(10, 10);

        $expectedSolution = [
            new Coordinate(0, 0),
            new Coordinate(2, 5),
            new Coordinate(3, 3),
            new Coordinate(6, 4),
            new Coordinate(10, 10)
        ];

        $expectedSolutionIds = array_map(static fn (Coordinate $coordinate) => $coordinate->getId(), $expectedSolution);

        $aStar = new AStar($this->sut);
        $solution = $aStar->run($start, $goal);

        $this->assertCount(count($expectedSolution), $solution);

        $solutionIds = array_map(static fn (Coordinate $coordinate) => $coordinate->getId(), (array) ($solution));

        $this->assertEquals($expectedSolutionIds, $solutionIds);
    }

    public function testShouldGetSolutionWithNodesFormingCircularPaths(): void
    {
        $nodes = [
            'start' => new Coordinate(0, 0),
            'intermediate' => new Coordinate(2, 5),
            'goal' => new Coordinate(6, 4)
        ];

        $links = [
            new Link($nodes['start'], $nodes['intermediate'], 6),
            new Link($nodes['intermediate'], $nodes['start'], 6),

            new Link($nodes['intermediate'], $nodes['goal'], 23),
            new Link($nodes['goal'], $nodes['intermediate'], 23),
        ];

        $graph = new Graph($links);

        $expectedSolution = [
            $nodes['start'],
            $nodes['intermediate'],
            $nodes['goal']
        ];

        $expectedSolutionIds = array_map(static fn (Coordinate $coordinate) => $coordinate->getId(), $expectedSolution);

        $this->sut = new DomainLogic($graph);

        $aStar = new AStar($this->sut);
        $solution = $aStar->run($nodes['start'], $nodes['goal']);

        $solutionIds = array_map(static fn (Coordinate $coordinate) => $coordinate->getId(), (array) ($solution));

        $this->assertEquals($expectedSolutionIds, $solutionIds);
    }

    /**
     * @param Coordinate $needle
     * @param Coordinate[] $haystack
     */
    private function assertContainsCoordinate(Coordinate $needle, iterable $haystack): void
    {
        foreach ($haystack as $node) {
            if ($needle->getId() === $node->getId()) {
                return;
            }
        }

        $this->fail(
            'Failed asserting that the array ' . print_r($haystack, true) .
            'contains the specified coordinate ' . print_r($needle, true)
        );
    }
}
