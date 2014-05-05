<?php

namespace JMGQ\AStar;

abstract class AStar
{
    /**
     * @param Node $node
     * @return Node[]
     */
    abstract public function generateAdjacentNodes(Node $node);

    /**
     * @param Node $start
     * @param Node $end
     * @return integer | float
     */
    abstract public function calculateRealDistance(Node $start, Node $end);

    /**
     * @param Node $start
     * @param Node $end
     * @return integer | float
     */
    abstract public function calculateHeuristicDistance(Node $start, Node $end);

    /**
     * @param Node $start
     * @param Node $goal
     * @return Node[]
     */
    public function run(Node $start, Node $goal)
    {
        $algorithm = new CallbackAlgorithm(
            $this,
            'generateAdjacentNodes',
            'calculateRealDistance',
            'calculateHeuristicDistance'
        );

        return $algorithm->run($start, $goal);
    }
}
