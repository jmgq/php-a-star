<?php

namespace JMGQ\AStar\Example\Terrain;

use JMGQ\AStar\AbstractNode;
use JMGQ\AStar\Node;

class MyNode extends AbstractNode
{
    private $row;
    private $column;

    public function __construct($row, $column)
    {
        $this->row = $this->filterNonNegativeInteger($row);
        $this->column = $this->filterNonNegativeInteger($column);
    }

    /**
     * @param Node $node
     * @return MyNode
     */
    public static function fromNode(Node $node)
    {
        $rowAndColumn = explode('x', $node->getID());

        if (count($rowAndColumn) != 2) {
            throw new \InvalidArgumentException('Invalid node: ' . print_r($node, true));
        }

        $row = $rowAndColumn[0];
        $column = $rowAndColumn[1];

        return new MyNode($row, $column);
    }

    public function getRow()
    {
        return $this->row;
    }

    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @inheritdoc
     */
    public function getID()
    {
        return $this->row . 'x' . $this->column;
    }

    private function filterNonNegativeInteger($value)
    {
        $nonNegativeInteger = filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 0)));

        if ($nonNegativeInteger === false) {
            throw new \InvalidArgumentException('Invalid non negative integer: ' . print_r($value, true));
        }

        return $nonNegativeInteger;
    }
}
