<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\Graph;
use JMGQ\AStar\Example\Graph\Link;
use JMGQ\AStar\Example\Graph\MyAStar;
use JMGQ\AStar\Example\Graph\MyNode;

class MyAStarTest extends \PHPUnit_Framework_TestCase
{
    /** @var MyAStar */
    private $sut;

    public function setUp()
    {
        $links = array(
            new Link(new MyNode(0, 0), new MyNode(2, 5), 3.5),
            new Link(new MyNode(0, 0), new MyNode(6, 4), 23.75),
            new Link(new MyNode(2, 5), new MyNode(3, 3), 5),
            new Link(new MyNode(3, 3), new MyNode(6, 4), 1.2),
            new Link(new MyNode(6, 4), new MyNode(10, 10), 2)
        );

        $graph = new Graph($links);

        $this->sut = new MyAStar($graph);
    }

    public function testShouldGenerateAdjacentNodes()
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

    public function testShouldCalculateRealCost()
    {
        $expectedCost = 1.2;

        $node = new MyNode(3, 3);
        $adjacentNode = new MyNode(6, 4);

        $cost = $this->sut->calculateRealCost($node, $adjacentNode);

        $this->assertSame($expectedCost, $cost);
    }

    /**
     * @expectedException \DomainException
     * @expectedExceptionMessage not linked
     */
    public function testShouldNotCalculateTheRealCostBetweenTwoUnlinkedNodes()
    {
        $node = new MyNode(6, 4);
        $nonAdjacentNode = new MyNode(3, 3);

        $this->sut->calculateRealCost($node, $nonAdjacentNode);
    }

    public function testShouldCalculateEstimatedCost()
    {
        $expectedCost = 20.2237484162;
        $maximumImprecisionAllowed = 0.0001;

        $startNode = new MyNode(-5, 6);
        $destinationNode = new MyNode(15, 9);

        $cost = $this->sut->calculateEstimatedCost($startNode, $destinationNode);

        $this->assertEquals($expectedCost, $cost, '', $maximumImprecisionAllowed);
    }

    /**
     * @param MyNode $needle
     * @param MyNode[] $haystack
     */
    private function assertContainsMyNode(MyNode $needle, array $haystack)
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
