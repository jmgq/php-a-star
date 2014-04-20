<?php

namespace JMGQ\AStar;

abstract class AbstractNode implements Node
{
    private $parent;
    private $children = array();

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
}
