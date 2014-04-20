<?php

namespace JMGQ\AStar;

interface Node
{
    public function getID();

    public function setParent(Node $parent);
    public function getParent();

    public function addChild(Node $child);
    public function getChildren();

    public function getF();

    public function setG($score);
    public function getG();

    public function setH($score);
    public function getH();
}
