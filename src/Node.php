<?php

namespace JMGQ\AStar;

interface Node
{
    /**
     * Obtains the node's unique ID
     * @return string
     */
    public function getID();

    /**
     * @param Node $parent
     */
    public function setParent(Node $parent);

    /**
     * @return Node | null
     */
    public function getParent();

    /**
     * @deprecated
     * @param Node $child
     */
    public function addChild(Node $child);

    /**
     * @deprecated
     * @return Node[]
     */
    public function getChildren();

    /**
     * @return integer | float
     */
    public function getF();

    /**
     * @param integer | float $score
     */
    public function setG($score);

    /**
     * @return integer | float
     */
    public function getG();

    /**
     * @param integer | float $score
     */
    public function setH($score);

    /**
     * @return integer | float
     */
    public function getH();
}
