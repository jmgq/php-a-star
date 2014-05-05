<?php

namespace JMGQ\AStar\Example;

use JMGQ\AStar\AbstractNode;

class MyNode extends AbstractNode
{
    private $row;
    private $column;

    public function __construct($row, $column)
    {
        $this->row = $this->filterNonNegativeInteger($row);
        $this->column = $this->filterNonNegativeInteger($column);
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
