<?php

namespace JMGQ\AStar\Example\Graph;

use JMGQ\AStar\AbstractNode;
use JMGQ\AStar\Node;

class MyNode extends AbstractNode
{
    private $x;
    private $y;

    public function __construct($x, $y)
    {
        $this->x = $this->filterInteger($x);
        $this->y = $this->filterInteger($y);
    }

    /**
     * @param Node $node
     * @return MyNode
     */
    public static function fromNode(Node $node)
    {
        $coordinates = explode('x', $node->getID());

        if (count($coordinates) !== 2) {
            throw new \InvalidArgumentException('Invalid node: ' . print_r($node, true));
        }

        $x = $coordinates[0];
        $y = $coordinates[1];

        return new MyNode($x, $y);
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    /**
     * {@inheritdoc}
     */
    public function getID()
    {
        return $this->x . 'x' . $this->y;
    }

    private function filterInteger($value)
    {
        $integer = filter_var($value, FILTER_VALIDATE_INT);

        if ($integer === false) {
            throw new \InvalidArgumentException('Invalid integer: ' . print_r($value, true));
        }

        return $integer;
    }
}
