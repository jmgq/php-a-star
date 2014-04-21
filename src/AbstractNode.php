<?php

namespace JMGQ\AStar;

abstract class AbstractNode implements Node
{
    private $parent;
    private $children = array();

    private $gScore;
    private $hScore;

    /**
     * @inheritdoc
     */
    public function setParent(Node $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @inheritdoc
     */
    public function addChild(Node $child)
    {
        $child->setParent($this);

        $this->children[] = $child;
    }

    /**
     * @inheritdoc
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @inheritdoc
     */
    public function getF()
    {
        return $this->getG() + $this->getH();
    }

    /**
     * @inheritdoc
     */
    public function setG($score)
    {
        $this->gScore = $score;
    }

    /**
     * @inheritdoc
     */
    public function getG()
    {
        return $this->gScore;
    }

    /**
     * @inheritdoc
     */
    public function setH($score)
    {
        $this->hScore = $score;
    }

    /**
     * @inheritdoc
     */
    public function getH()
    {
        return $this->hScore;
    }
}
