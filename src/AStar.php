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
     * @param Node $node
     * @param Node $adjacent
     * @return integer | float
     */
    abstract public function calculateRealCost(Node $node, Node $adjacent);

    /**
     * @param Node $start
     * @param Node $end
     * @return integer | float
     */
    abstract public function calculateEstimatedCost(Node $start, Node $end);

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
            'calculateRealCost',
            'calculateEstimatedCost'
        );

        return $algorithm->run($start, $goal);
    }
}
