<?php

namespace JMGQ\AStar;

class CallbackAlgorithm extends Algorithm
{
    private $object;
    private $adjacentNodesCallback;
    private $realCostCallback;
    private $estimatedCostCallback;

    public function __construct($object, $adjacentNodesCallback, $realCostCallback, $estimatedCostCallback)
    {
        parent::__construct();

        $this->object = $object;
        $this->adjacentNodesCallback = $adjacentNodesCallback;
        $this->realCostCallback = $realCostCallback;
        $this->estimatedCostCallback = $estimatedCostCallback;
    }

    /**
     * @param Node $node
     * @return Node[]
     */
    public function generateAdjacentNodes(Node $node)
    {
        return call_user_func_array(array($this->object, $this->adjacentNodesCallback), array($node));
    }

    /**
     * @param Node $node
     * @param Node $adjacent
     * @return integer | float
     */
    public function calculateRealCost(Node $node, Node $adjacent)
    {
        return call_user_func_array(array($this->object, $this->realCostCallback), array($node, $adjacent));
    }

    /**
     * @param Node $start
     * @param Node $end
     * @return integer | float
     */
    public function calculateEstimatedCost(Node $start, Node $end)
    {
        return call_user_func_array(array($this->object, $this->estimatedCostCallback), array($start, $end));
    }
}
