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
     * @deprecated
     * @param Node $child
     */
    public function addChild(Node $child)
    {
        $child->setParent($this);

        $this->children[] = $child;
    }

    /**
     * @deprecated
     * @return Node[]
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
        if (!is_numeric($score)) {
            throw new \InvalidArgumentException('The G value is not a number');
        }

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
        if (!is_numeric($score)) {
            throw new \InvalidArgumentException('The H value is not a number');
        }

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
