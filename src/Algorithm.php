<?php

namespace JMGQ\AStar;

class Algorithm
{
    private $openList;
    private $closedList;

    public function __construct()
    {
        $this->openList = new NodeList();
        $this->closedList = new NodeList();
    }

    public function getOpenList()
    {
        return $this->openList;
    }

    public function getClosedList()
    {
        return $this->closedList;
    }
}
