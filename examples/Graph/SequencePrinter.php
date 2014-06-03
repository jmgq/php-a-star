<?php

namespace JMGQ\AStar\Example\Graph;

class SequencePrinter
{
    private $graph;
    private $sequence;

    /**
     * @param Graph $graph
     * @param MyNode[] $sequence
     */
    public function __construct(Graph $graph, array $sequence)
    {
        $this->graph = $graph;
        $this->sequence = $sequence;
    }

    public function printSequence()
    {
        $nodesAsString = array();

        foreach ($this->sequence as $node) {
            $nodesAsString[] = $this->getNodeAsString($node);
        }

        if (!empty($nodesAsString)) {
            echo implode(' => ', $nodesAsString);
            echo "\n";
        }

        echo 'Total cost: ' . $this->getTotalDistance();
    }

    private function getNodeAsString(MyNode $node)
    {
        return "({$node->getX()}, {$node->getY()})";
    }

    private function getTotalDistance()
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
