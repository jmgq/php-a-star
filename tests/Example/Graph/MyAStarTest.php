<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\Graph;
use JMGQ\AStar\Example\Graph\Link;
use JMGQ\AStar\Example\Graph\MyAStar;
use JMGQ\AStar\Example\Graph\MyNode;
use PHPUnit\Framework\TestCase;

class MyAStarTest extends TestCase
{
    private MyAStar $sut;

    protected function setUp(): void
    {
        $links = array(
            new Link(new MyNode(0, 0), new MyNode(2, 5), 6.5),
            new Link(new MyNode(0, 0), new MyNode(6, 4), 23.75),
            new Link(new MyNode(2, 5), new MyNode(3, 3), 5),
            new Link(new MyNode(3, 3), new MyNode(6, 4), 3.2),
            new Link(new MyNode(6, 4), new MyNode(10, 10), 8)
        );

        $graph = new Graph($links);

        $this->sut = new MyAStar($graph);
    }

    public function testShouldGenerateAdjacentNodes(): void
    {
        $node = new MyNode(0, 0);
        $expectedAdjacentNodes = array(
            new MyNode(2, 5),
            new MyNode(6, 4)
        );

        $adjacentNodes = $this->sut->generateAdjacentNodes($node);

        $this->assertCount(count($expectedAdjacentNodes), $adjacentNodes);

        foreach ($expectedAdjacentNodes as $expectedNode) {
            $this->assertContainsMyNode($expectedNode, $adjacentNodes);
        }
    }

    public function testShouldCalculateRealCost(): void
    {
        $expectedCost = 3.2;

        $node = new MyNode(3, 3);
        $adjacentNode = new MyNode(6, 4);

        $cost = $this->sut->calculateRealCost($node, $adjacentNode);

        $this->assertSame($expectedCost, $cost);
    }

    public function testShouldNotCalculateTheRealCostBetweenTwoUnlinkedNodes(): void
    {
        $node = new MyNode(6, 4);
        $nonAdjacentNode = new MyNode(3, 3);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('not linked');

        $this->sut->calculateRealCost($node, $nonAdjacentNode);
    }

    public function testShouldCalculateEstimatedCost(): void
    {
        $expectedCost = 20.2237484162;
        $maximumImprecisionAllowed = 0.0001;

        $startNode = new MyNode(-5, 6);
        $destinationNode = new MyNode(15, 9);

        $cost = $this->sut->calculateEstimatedCost($startNode, $destinationNode);

        $this->assertEqualsWithDelta($expectedCost, $cost, $maximumImprecisionAllowed);
    }

    public function testShouldGetRightSolution(): void
    {
        $start = new MyNode(0, 0);
        $goal = new MyNode(10, 10);

        $expectedSolution = array(
            new MyNode(0, 0),
            new MyNode(2, 5),
            new MyNode(3, 3),
            new MyNode(6, 4),
            new MyNode(10, 10)
        );

        $solution = $this->sut->run($start, $goal);

        $this->assertCount(count($expectedSolution), $solution);

        for ($i = 0; $i < count($expectedSolution); $i++) {
            $this->assertSame($expectedSolution[$i]->getID(), $solution[$i]->getID());
        }
    }

    public function testShouldGetSolutionWithNodesFormingCircularPaths(): void
    {
        $nodes = array(
            'start' => new MyNode(0, 0),
            'intermediate' => new MyNode(2, 5),
            'goal' => new MyNode(6, 4)
        );

        $links = array(
            new Link($nodes['start'], $nodes['intermediate'], 6),
            new Link($nodes['intermediate'], $nodes['start'], 6),

            new Link($nodes['intermediate'], $nodes['goal'], 23),
            new Link($nodes['goal'], $nodes['intermediate'], 23),
        );

        $graph = new Graph($links);

        $expectedSolution = array(
            $nodes['start'],
            $nodes['intermediate'],
            $nodes['goal']
        );

        $this->sut = new MyAStar($graph);

        $solution = $this->sut->run($nodes['start'], $nodes['goal']);

        for ($i = 0; $i < count($expectedSolution); $i++) {
            $this->assertSame($expectedSolution[$i]->getID(), $solution[$i]->getID());
        }
    }

    /**
     * @param MyNode $needle
     * @param MyNode[] $haystack
     */
    private function assertContainsMyNode(MyNode $needle, array $haystack): void
    {
        foreach ($haystack as $node) {
            if ($needle->getID() === $node->getID()) {
                return;
            }
        }

        $this->fail(
            'Failed asserting that the array ' . print_r($haystack, true) .
            'contains the specified node ' . print_r($needle, true)
        );
    }
}
