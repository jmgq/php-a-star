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

    /**
     * @param Node $start
     * @param Node $goal
     * @return Node[]
     */
    public function run(Node $start, Node $goal)
    {
        $path = array();

        $this->clear();

        $start->setG(0);
        $start->setH($this->calculateHeuristicDistance($start, $goal));

        $this->getOpenList()->add($start);

        while (!$this->getOpenList()->isEmpty()) {
            $currentNode = $this->getOpenList()->extractBest();

            $this->getClosedList()->add($currentNode);

            if ($currentNode->getID() === $goal->getID()) {
                $path = $this->generatePathFromStartNodeTo($currentNode);
                break;
            }

            $successors = $this->computeAdjacentNodes($currentNode, $goal);

            foreach ($successors as $successor) {
                if ($this->getOpenList()->contains($successor)) {
                    $successorInOpenList = $this->getOpenList()->get($successor);

                    if ($successor->getF() >= $successorInOpenList->getF()) {
                        continue;
                    }
                } elseif ($this->getClosedList()->contains($successor)) {
                    $successorInClosedList = $this->getClosedList()->get($successor);

                    if ($successor->getF() >= $successorInClosedList->getF()) {
                        continue;
                    }
                } else {
                    $this->getOpenList()->remove($successor);
                    $this->getClosedList()->remove($successor);

                    $this->getOpenList()->add($successor);
                }
            }
        }

        return $path;
    }

    private function generatePathFromStartNodeTo(Node $node)
    {
        $path = array();

        $currentNode = $node;

        while ($currentNode !== null) {
            array_unshift($path, $currentNode);

            $currentNode = $currentNode->getParent();
        }

        return $path;
    }

    private function computeAdjacentNodes(Node $node, Node $goal)
    {
        $nodes = $this->generateAdjacentNodes($node);

        foreach ($nodes as $adjacentNode) {
            $adjacentNode->setParent($node);
            $adjacentNode->setG($node->getG() + $this->calculateRealDistance($node, $adjacentNode));
            $adjacentNode->setH($this->calculateHeuristicDistance($adjacentNode, $goal));
        }

        return $nodes;
    }
}
