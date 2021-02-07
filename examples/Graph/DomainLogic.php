<?php

namespace JMGQ\AStar\Example\Graph;

use JMGQ\AStar\DomainLogicInterface;

class DomainLogic implements DomainLogicInterface
{
    public function __construct(private Graph $graph)
    {
    }

    /**
     * @param Coordinate $node
     * @return Coordinate[]
     */
    public function getAdjacentNodes(mixed $node): iterable
    {
        return $this->graph->getDirectSuccessors($node);
    }

    /**
     * @param Coordinate $node
     * @param Coordinate $adjacent
     * @return float|int
     */
    public function calculateRealCost(mixed $node, mixed $adjacent): float | int
    {
        if (!$this->graph->hasLink($node, $adjacent)) {
            throw new \DomainException('The provided nodes are not linked');
        }

        return $this->graph->getLink($node, $adjacent)->getDistance();
    }

    /**
     * @param Coordinate $start
     * @param Coordinate $end
     * @return float|int
     */
    public function calculateEstimatedCost(mixed $start, mixed $end): float | int
    {
        return $this->euclideanDistance($start, $end);
    }

    private function euclideanDistance(Coordinate $a, Coordinate $b): float
    {
        $xFactor = ($a->getX() - $b->getX()) ** 2;
        $yFactor = ($a->getY() - $b->getY()) ** 2;

        return sqrt($xFactor + $yFactor);
    }
}
