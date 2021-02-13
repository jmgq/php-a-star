<?php

namespace JMGQ\AStar\Example\Graph;

use JMGQ\AStar\DomainLogicInterface;

/**
 * @implements DomainLogicInterface<Coordinate>
 */
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

        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress PossiblyNullReference
         * getLink cannot be null, as we just checked that the link exists
         */
        return $this->graph->getLink($node, $adjacent)->getDistance();
    }

    /**
     * @param Coordinate $fromNode
     * @param Coordinate $toNode
     * @return float|int
     */
    public function calculateEstimatedCost(mixed $fromNode, mixed $toNode): float | int
    {
        return $this->euclideanDistance($fromNode, $toNode);
    }

    private function euclideanDistance(Coordinate $a, Coordinate $b): float
    {
        $xFactor = ($a->getX() - $b->getX()) ** 2;
        $yFactor = ($a->getY() - $b->getY()) ** 2;

        return sqrt($xFactor + $yFactor);
    }
}
