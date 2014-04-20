<?php

namespace JMGQ\AStar;

class Algorithm
{
    private $openList = array();
    private $closedList = array();

    public function getOpenList()
    {
        return $this->openList;
    }

    public function getClosedList()
    {
        return $this->closedList;
    }
}
