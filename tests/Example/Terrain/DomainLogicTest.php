<?php

namespace JMGQ\AStar\Tests\Example\Terrain;

use JMGQ\AStar\DomainLogicInterface;
use JMGQ\AStar\Example\Terrain\DomainLogic;
use JMGQ\AStar\Example\Terrain\Position;
use JMGQ\AStar\Example\Terrain\TerrainCost;
use PHPUnit\Framework\TestCase;

class DomainLogicTest extends TestCase
{
    private DomainLogic $sut;

    /**
     * @return mixed[][]
     */
    public function adjacentNodesProvider(): array
    {
        return [
            [
                'node' => new Position(2, 0),
                'adjacent' => [
                    new Position(1, 0),
                    new Position(1, 1),
                    new Position(2, 1),
                ]
            ],
            [
                'node' => new Position(0, 2),
                'adjacent' => [
                    new Position(0, 1),
                    new Position(1, 1),
                    new Position(1, 2),
                ]
            ],
            [
                'node' => new Position(1, 1),
                'adjacent' => [
                    new Position(0, 0),
                    new Position(0, 1),
                    new Position(0, 2),
                    new Position(1, 0),
                    new Position(1, 2),
                    new Position(2, 0),
                    new Position(2, 1),
                    new Position(2, 2),
                ]
            ]
        ];
    }

    protected function setUp(): void
    {
        $terrainCost = new TerrainCost([
            [1, 3, 5],
            [2, 8, 1],
            [1, 1, 1]
        ]);

        $this->sut = new DomainLogic($terrainCost);
    }

    public function testShouldImplementTheDomainLogicInterface(): void
    {
        $this->assertInstanceOf(DomainLogicInterface::class, $this->sut);
    }

    /**
     * @dataProvider adjacentNodesProvider
     * @param Position $node
     * @param Position[] $expectedAdjacentNodes
     */
    public function testShouldGenerateAdjacentNodes(Position $node, array $expectedAdjacentNodes): void
    {
        $adjacentNodes = $this->sut->getAdjacentNodes($node);

        $this->assertCount(count($expectedAdjacentNodes), $adjacentNodes);

        foreach ($expectedAdjacentNodes as $expectedPosition) {
            $this->assertContainsPosition($expectedPosition, $adjacentNodes);
        }
    }

    public function testShouldCalculateRealCost(): void
    {
        $expectedCost = 3;

        $node = new Position(1, 0);
        $adjacentNode = new Position(0, 1);

        $cost = $this->sut->calculateRealCost($node, $adjacentNode);

        $this->assertSame($expectedCost, $cost);
    }

    public function testTheCostBetweenNonAdjacentNodesShouldBeInfinite(): void
    {
        $expectedCost = TerrainCost::INFINITE;

        $node = new Position(0, 0);
        $nonAdjacentNode = new Position(0, 2);

        $cost = $this->sut->calculateRealCost($node, $nonAdjacentNode);

        $this->assertSame($expectedCost, $cost);
    }

    public function testShouldCalculateEstimatedCost(): void
    {
        $expectedCost = sqrt(5);
        $maximumImprecisionAllowed = 0.0001;

        $startNode = new Position(1, 0);
        $destinationNode = new Position(0, 2);

        $cost = $this->sut->calculateEstimatedCost($startNode, $destinationNode);

        $this->assertEqualsWithDelta($expectedCost, $cost, $maximumImprecisionAllowed);
    }

    public function testShouldNotGenerateNewNodesAfterEveryCallToGetAdjacentNodes(): void
    {
        $node = new Position(0, 0);

        $adjacentNodes = $this->sut->getAdjacentNodes($node);
        $sameAdjacentNodes = $this->sut->getAdjacentNodes($node);

        $this->assertEquals($adjacentNodes, $sameAdjacentNodes);
    }

    /**
     * @param Position $needle
     * @param Position[] $haystack
     */
    private function assertContainsPosition(Position $needle, iterable $haystack): void
    {
        foreach ($haystack as $position) {
            if ($needle->isEqualTo($position)) {
                return;
            }
        }

        $this->fail(
            'Failed asserting that the array ' . print_r($haystack, true) .
            'contains the specified position ' . print_r($needle, true)
        );
    }
}
