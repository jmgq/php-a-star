<?php

namespace JMGQ\AStar;

class NodeList implements \IteratorAggregate
{
    private $nodeList = array();

    public function getIterator()
    {
        return new \ArrayIterator($this->nodeList);
    }
}
