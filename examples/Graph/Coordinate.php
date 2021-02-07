<?php

namespace JMGQ\AStar\Example\Graph;

class Coordinate
{
    public function __construct(private int $x, private int $y)
    {
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getId(): string
    {
        return $this->x . 'x' . $this->y;
    }
}
