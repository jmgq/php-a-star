<?php

namespace JMGQ\AStar;

/**
 * @template TNode
 */
interface DomainLogicInterface
{
    /**
     * @param TNode $node
     * @return TNode[]
     */
    public function getAdjacentNodes(mixed $node): iterable;

    /**
     * @param TNode $node
     * @param TNode $adjacent
     * @return float | int
     */
    public function calculateRealCost(mixed $node, mixed $adjacent): float | int;

    /**
     * @param TNode $fromNode
     * @param TNode $toNode
     * @return float | int
     */
    public function calculateEstimatedCost(mixed $fromNode, mixed $toNode): float | int;
}
