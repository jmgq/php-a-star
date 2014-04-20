<?php

namespace JMGQ\AStar;

abstract class AbstractNode implements Node
{
    private $parent;
    private $children = array();

    private $gScore;
    private $hScore;

    public function setParent(Node $parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function addChild(Node $child)
    {
        $child->setParent($this);

        $this->children[] = $child;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getF()
    {
        return $this->getG() + $this->getH();
    }

    public function setG($score)
    {
        $this->gScore = $score;
    }

    public function getG()
    {
        return $this->gScore;
    }

    public function setH($score)
    {
        $this->hScore = $score;
    }

    public function getH()
    {
        return $this->hScore;
    }
}
