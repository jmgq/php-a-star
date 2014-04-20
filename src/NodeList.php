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
        $this->nodeList[] = $node;
    }

    public function isEmpty()
    {
        return empty($this->nodeList);
    }
}
