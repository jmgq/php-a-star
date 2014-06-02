<?php

namespace JMGQ\AStar\Example\Graph;

use JMGQ\AStar\AStar;
use JMGQ\AStar\Node;

class MyAStar extends AStar
{
    private $graph;

    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * @inheritdoc
     */
    public function generateAdjacentNodes(Node $node)
    {
        $myNode = MyNode::fromNode($node);

        return $this->graph->getDirectSuccessors($myNode);
    }

    /**
     * @inheritdoc
     */
    public function calculateRealCost(Node $node, Node $adjacent)
    {
        $myStartNode = MyNode::fromNode($node);
        $myEndNode = MyNode::fromNode($adjacent);

        if (!$this->graph->hasLink($myStartNode, $myEndNode)) {
            throw new \DomainException('The provided nodes are not linked');
        }

        return $this->graph->getLink($myStartNode, $myEndNode)->getDistance();
    }

    /**
     * @inheritdoc
     */
    public function calculateEstimatedCost(Node $start, Node $end)
    {
        $myStartNode = MyNode::fromNode($start);
        $myEndNode = MyNode::fromNode($end);

        $xFactor = pow($myStartNode->getX() - $myEndNode->getX(), 2);
        $yFactor = pow($myStartNode->getY() - $myEndNode->getY(), 2);

        $euclideanDistance = sqrt($xFactor + $yFactor);

        return $euclideanDistance;
    }
}
