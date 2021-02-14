<?php

namespace JMGQ\AStar;

/**
 * @template TState
 */
interface DomainLogicInterface
{
    /**
     * @param TState $node
     * @return TState[]
     */
    public function getAdjacentNodes(mixed $node): iterable;

    /**
     * @param TState $node
     * @param TState $adjacent
     * @return float | int
     */
    public function calculateRealCost(mixed $node, mixed $adjacent): float | int;

    /**
     * @param TState $fromNode
     * @param TState $toNode
     * @return float | int
     */
    public function calculateEstimatedCost(mixed $fromNode, mixed $toNode): float | int;
}
