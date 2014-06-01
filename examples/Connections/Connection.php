<?php

namespace JMGQ\AStar\Example\Connections;

class Connection
{
    private $source;
    private $destination;
    private $distance;

    public function __construct(MyNode $source, MyNode $destination, $distance)
    {
        $this->source = $source;
        $this->destination = $destination;

        $filteredDistance = filter_var($distance, FILTER_VALIDATE_FLOAT);

        if ($filteredDistance === false || $filteredDistance < 0) {
            throw new \InvalidArgumentException('Invalid distance: ' . print_r($distance, true));
        }

        $this->distance = $filteredDistance;
    }

    /**
     * @return MyNode
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return MyNode
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }
}
