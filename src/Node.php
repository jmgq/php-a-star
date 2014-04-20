<?php

namespace JMGQ\AStar;

interface Node
{
    public function getID();

    public function setParent(Node $parent);
    public function getParent();

    public function addChild(Node $child);
    public function getChildren();
}
