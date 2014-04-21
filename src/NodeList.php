<?php

namespace JMGQ\AStar;

class NodeList implements \IteratorAggregate
{
    private $nodeList = array();

    public function getIterator()
    {
        return new \ArrayIterator($this->nodeList);
    }

    public function add(Node $node)
    {
        $this->nodeList[$node->getID()] = $node;
    }

    public function isEmpty()
    {
        return empty($this->nodeList);
    }

    public function contains(Node $node)
    {
        return isset($this->nodeList[$node->getID()]);
    }

    public function getBest()
    {
        $bestNode = null;

        foreach ($this->nodeList as $node) {
            if ($bestNode === null || $node->getF() < $bestNode->getF()) {
                $bestNode = $node;
            }
        }

        return $bestNode;
    }

    public function remove(Node $node)
    {
        unset($this->nodeList[$node->getID()]);
    }

    public function get(Node $node)
    {
        if ($this->contains($node)) {
            return $this->nodeList[$node->getID()];
        }

        return null;
    }
}
