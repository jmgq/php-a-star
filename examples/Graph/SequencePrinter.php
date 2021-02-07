<?php

namespace JMGQ\AStar\Example\Graph;

class SequencePrinter
{
    /**
     * @param Graph $graph
     * @param Coordinate[] $sequence
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

        if (!empty($coordinatesAsString)) {
            echo implode(' => ', $coordinatesAsString);
            echo "\n";
        }

        echo 'Total cost: ' . $this->getTotalDistance();
    }

    private function getCoordinateAsString(Coordinate $coordinate): string
    {
        return "({$coordinate->getX()}, {$coordinate->getY()})";
    }

    private function getTotalDistance(): float | int
    {
        if (count($this->sequence) < 2) {
            return 0;
        }

        $totalDistance = 0;

        $sequence = $this->sequence;

        $previousNode = array_shift($sequence);
        foreach ($sequence as $node) {
            $totalDistance += $this->graph->getLink($previousNode, $node)->getDistance();

            $previousNode = $node;
        }

        return $totalDistance;
    }
}
