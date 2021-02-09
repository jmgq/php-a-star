<?php

namespace JMGQ\AStar\Example\Terrain;

use JMGQ\AStar\Node\NodeIdentifierInterface;

class Position implements NodeIdentifierInterface
{
    private int $row;
    private int $column;

    public function __construct(int $row, int $column)
    {
        $this->validateNonNegativeInteger($row);
        $this->validateNonNegativeInteger($column);

        $this->row = $row;
        $this->column = $column;
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    public function isEqualTo(Position $other): bool
    {
        return $this->getRow() === $other->getRow() && $this->getColumn() === $other->getColumn();
    }

    public function isAdjacentTo(Position $other): bool
    {
        return abs($this->getRow() - $other->getRow()) <= 1 && abs($this->getColumn() - $other->getColumn()) <= 1;
    }

    public function getUniqueNodeId(): string
    {
        return $this->row . 'x' . $this->column;
    }

    private function validateNonNegativeInteger(int $integer): void
    {
        if ($integer < 0) {
            throw new \InvalidArgumentException("Invalid non negative integer: $integer");
        }
    }
}
