<?php

namespace JMGQ\AStar\Tests\Example;

use JMGQ\AStar\Example\MyAStar;
use JMGQ\AStar\Example\MyNode;
use JMGQ\AStar\Example\TerrainCost;

class MyAStarTest extends \PHPUnit_Framework_TestCase
{
    /** @var MyAStar */
    private $sut;

    public function adjacentNodesProvider()
    {
        return array(
            array(
                'node' => new MyNode(2, 0),
                'adjacent' => array(
                    new MyNode(1, 0),
                    new MyNode(1, 1),
                    new MyNode(2, 1)
                )
            ),
            array(
                'node' => new MyNode(0, 2),
                'adjacent' => array(
                    new MyNode(0, 1),
                    new MyNode(1, 1),
                    new MyNode(1, 2)
                )
            ),
            array(
                'node' => new MyNode(1, 1),
                'adjacent' => array(
                    new MyNode(0, 0),
                    new MyNode(0, 1),
                    new MyNode(0, 2),
                    new MyNode(1, 0),
                    new MyNode(1, 2),
                    new MyNode(2, 0),
                    new MyNode(2, 1),
                    new MyNode(2, 2)
                )
            )
        );
    }

    public function setUp()
    {
        $terrainCost = new TerrainCost(
            array(
                array(1, 3, 5),
                array(2, 8, 1),
                array(1, 1, 1)
            )
        );

        $this->sut = new MyAStar($terrainCost);
    }

    /**
     * @dataProvider adjacentNodesProvider
     */
    public function testShouldGenerateAdjacentNodes(MyNode $node, array $expectedAdjacentNodes)
    {
        $adjacentNodes = $this->sut->generateAdjacentNodes($node);

        $this->assertCount(count($expectedAdjacentNodes), $adjacentNodes);

        foreach ($expectedAdjacentNodes as $expectedNode) {
            $this->assertContainsMyNode($expectedNode, $adjacentNodes);
        }
    }

    public function testShouldCalculateRealDistance()
    {
        $expectedDistance = 3;

        $startNode = new MyNode(1, 0);
        $destinationNode = new MyNode(0, 1);

        $distance = $this->sut->calculateRealDistance($startNode, $destinationNode);

        $this->assertSame($expectedDistance, $distance);
    }

    public function testTheDistanceBetweenNonAdjacentNodesShouldBeInfinite()
    {
        $expectedDistance = TerrainCost::INFINITE;

        $startNode = new MyNode(0, 0);
        $nonAdjacentNode = new MyNode(0, 2);

        $distance = $this->sut->calculateRealDistance($startNode, $nonAdjacentNode);

        $this->assertSame($expectedDistance, $distance);
    }

    public function testShouldCalculateHeuristicDistance()
    {
        $expectedDistance = sqrt(5);
        $maximumImprecisionAllowed = 0.0001;

        $startNode = new MyNode(1, 0);
        $destinationNode = new MyNode(0, 2);

        $distance = $this->sut->calculateHeuristicDistance($startNode, $destinationNode);

        $this->assertEquals($expectedDistance, $distance, '', $maximumImprecisionAllowed);
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
