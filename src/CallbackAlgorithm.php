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
     * {@inheritdoc}
     */
    public function generateAdjacentNodes(Node $node)
    {
        return call_user_func(array($this->object, $this->adjacentNodesCallback), $node);
    }

    /**
     * {@inheritdoc}
     */
    public function calculateRealCost(Node $node, Node $adjacent)
    {
        return call_user_func(array($this->object, $this->realCostCallback), $node, $adjacent);
    }

    /**
     * {@inheritdoc}
     */
    public function calculateEstimatedCost(Node $start, Node $end)
    {
        return call_user_func(array($this->object, $this->estimatedCostCallback), $start, $end);
    }
}
