<?php

namespace JMGQ\AStar\Example\Graph;

class Link
{
    private Coordinate $source;
    private Coordinate $destination;
    private float $distance;

    public function __construct(Coordinate $source, Coordinate $destination, float $distance)
    {
        if ($distance < 0) {
            throw new \InvalidArgumentException("Invalid distance: $distance");
        }

        $this->source = $source;
        $this->destination = $destination;
        $this->distance = $distance;
    }

    public function getSource(): Coordinate
    {
        return $this->source;
    }

    public function getDestination(): Coordinate
    {
        return $this->destination;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }
}
