<?php

namespace JMGQ\AStar\Example\Terrain;

use JMGQ\AStar\DomainLogicInterface;

class DomainLogic implements DomainLogicInterface
{
    private TerrainCost $terrainCost;
    /** @var Position[][] */
    private array $positions;

    public function __construct(TerrainCost $terrainCost)
    {
        $this->terrainCost = $terrainCost;

        // We store all the Position objects in a matrix for efficiency, so that we don't need to create new Position
        // instances every time we call "getAdjacentNodes". If we created new positions with every call, the algorithm
        // would still work correctly but it would be a bit slower.
        $this->positions = $this->generatePositions($terrainCost);
    }

    /**
     * @param Position $node
     * @return Position[]
     */
    public function getAdjacentNodes(mixed $node): iterable
    {
        $adjacentNodes = [];

        if ($node->getRow() === 0) {
            $startingRow = 0;
        } else {
            $startingRow = $node->getRow() - 1;
        }

        if ($node->getRow() === $this->terrainCost->getTotalRows() - 1) {
            $endingRow = $node->getRow();
        } else {
            $endingRow = $node->getRow() + 1;
        }

        if ($node->getColumn() === 0) {
            $startingColumn = 0;
        } else {
            $startingColumn = $node->getColumn() - 1;
        }

        if ($node->getColumn() === $this->terrainCost->getTotalColumns() - 1) {
            $endingColumn = $node->getColumn();
        } else {
            $endingColumn = $node->getColumn() + 1;
        }

        for ($row = $startingRow; $row <= $endingRow; $row++) {
            for ($column = $startingColumn; $column <= $endingColumn; $column++) {
                $adjacentNode = $this->positions[$row][$column];

                if (!$node->isEqualTo($adjacentNode)) {
                    $adjacentNodes[] = $adjacentNode;
                }
            }
        }

        return $adjacentNodes;
    }

    /**
     * @param Position $node
     * @param Position $adjacent
     * @return float|int
     */
    public function calculateRealCost(mixed $node, mixed $adjacent): float | int
    {
        if ($node->isAdjacentTo($adjacent)) {
            return $this->terrainCost->getCost($adjacent->getRow(), $adjacent->getColumn());
        }

        return TerrainCost::INFINITE;
    }

    /**
     * @param Position $fromNode
     * @param Position $toNode
     * @return float|int
     */
    public function calculateEstimatedCost(mixed $fromNode, mixed $toNode): float | int
    {
        return $this->euclideanDistance($fromNode, $toNode);
    }

    private function euclideanDistance(Position $a, Position $b): float
    {
        $rowFactor = ($a->getRow() - $b->getRow()) ** 2;
        $columnFactor = ($a->getColumn() - $b->getColumn()) ** 2;

        return sqrt($rowFactor + $columnFactor);
    }

    /**
     * @param TerrainCost $terrainCost
     * @return Position[][]
     */
    private function generatePositions(TerrainCost $terrainCost): array
    {
        $positions = [];

        for ($row = 0; $row < $terrainCost->getTotalRows(); $row++) {
            for ($column = 0; $column < $terrainCost->getTotalColumns(); $column++) {
                $positions[$row][$column] = new Position($row, $column);
            }
        }

        return $positions;
    }
}
