<?php

namespace JMGQ\AStar;

class CallbackAlgorithm extends Algorithm
{
    private $object;
    private $adjacentNodesCallback;
    private $realDistanceCallback;
    private $heuristicDistanceCallback;

    public function __construct($object, $adjacentNodesCallback, $realDistanceCallback, $heuristicDistanceCallback)
    {
        parent::__construct();

        $this->object = $object;
        $this->adjacentNodesCallback = $adjacentNodesCallback;
        $this->realDistanceCallback = $realDistanceCallback;
        $this->heuristicDistanceCallback = $heuristicDistanceCallback;
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
     * @param Node $start
     * @param Node $end
     * @return integer | float
     */
    public function calculateRealDistance(Node $start, Node $end)
    {
        return call_user_func_array(array($this->object, $this->realDistanceCallback), array($start, $end));
    }

    /**
     * @param Node $start
     * @param Node $end
     * @return integer | float
     */
    public function calculateHeuristicDistance(Node $start, Node $end)
    {
        return call_user_func_array(array($this->object, $this->heuristicDistanceCallback), array($start, $end));
    }
}
