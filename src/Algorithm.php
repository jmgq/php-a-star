<?php

namespace JMGQ\AStar;

abstract class Algorithm
{
    private $openList;
    private $closedList;

    public function __construct()
    {
        $this->openList = new NodeList();
        $this->closedList = new NodeList();
    }

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
     * @return NodeList
     */
    public function getOpenList()
    {
        return $this->openList;
    }

    /**
     * @return NodeList
     */
    public function getClosedList()
    {
        return $this->closedList;
    }

    /**
     * Sets the algorithm to its initial state
     */
    public function clear()
    {
        $this->getOpenList()->clear();
        $this->getClosedList()->clear();
    }
}
