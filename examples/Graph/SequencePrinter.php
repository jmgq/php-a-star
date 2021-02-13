<?php

namespace JMGQ\AStar\Example\Graph;

class SequencePrinter
{
    /**
     * @param Graph $graph
     * @param iterable<Coordinate> $sequence
     */
    public function __construct(private Graph $graph, private iterable $sequence)
    {
    }

    public function printSequence(): void
    {
        $coordinatesAsString = [];

        foreach ($this->sequence as $coordinate) {
            $coordinatesAsString[] = $this->getCoordinateAsString($coordinate);
        }

        $cost = $this->getTotalDistance();

        if (!empty($coordinatesAsString)) {
            echo implode(' => ', $coordinatesAsString);
            echo "\n";
        }

        echo "Total cost: $cost";
    }

    private function getCoordinateAsString(Coordinate $coordinate): string
    {
        return "({$coordinate->getX()}, {$coordinate->getY()})";
    }

    private function getTotalDistance(): float | int
    {
        /** @var Coordinate[] $sequence */
        $sequence = (array) $this->sequence;

        if (count($sequence) < 2) {
            return 0;
        }

        $totalDistance = 0;

        $previousNode = array_shift($sequence);
        foreach ($sequence as $node) {
            $link = $this->graph->getLink($previousNode, $node);

            if (!$link) {
                throw new \RuntimeException('Some of the nodes in the provided sequence are not connected');
            }

            $totalDistance += $link->getDistance();

            $previousNode = $node;
        }

        return $totalDistance;
    }
}
