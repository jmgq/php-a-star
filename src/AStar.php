<?php

namespace JMGQ\AStar;

use JMGQ\AStar\Node\Collection\NodeCollectionInterface;
use JMGQ\AStar\Node\Collection\NodeHashTable;
use JMGQ\AStar\Node\Node;

/**
 * @template TNode
 */
class AStar
{
    /** @var DomainLogicInterface<TNode> */
    private DomainLogicInterface $domainLogic;
    private NodeCollectionInterface $openList;
    private NodeCollectionInterface $closedList;

    /**
     * @param DomainLogicInterface<TNode> $domainLogic
     */
    public function __construct(DomainLogicInterface $domainLogic)
    {
        $this->domainLogic = $domainLogic;
        $this->openList = new NodeHashTable();
        $this->closedList = new NodeHashTable();
    }

    /**
     * @param TNode $start
     * @param TNode $goal
     * @return TNode[]
     */
    public function run(mixed $start, mixed $goal): iterable
    {
        $startNode = new Node($start);
        $goalNode = new Node($goal);

        return $this->executeAlgorithm($startNode, $goalNode);
    }

    /**
     * @param Node $start
     * @param Node $goal
     * @return TNode[]
     */
    private function executeAlgorithm(Node $start, Node $goal): iterable
    {
        $path = [];

        $this->clear();

        $start->setG(0);
        $start->setH($this->calculateEstimatedCost($start, $goal));

        $this->openList->add($start);

        while (!$this->openList->isEmpty()) {
            /** @var Node $currentNode Cannot be null because the open list is not empty */
            $currentNode = $this->openList->extractBest();

            $this->closedList->add($currentNode);

            if ($currentNode->getId() === $goal->getId()) {
                $path = $this->generatePathFromStartNodeTo($currentNode);
                break;
            }

            $successors = $this->getAdjacentNodesWithTentativeScore($currentNode, $goal);

            foreach ($successors as $successor) {
                if ($this->nodeAlreadyPresentInListWithBetterOrSameRealCost($successor, $this->openList)) {
                    continue;
                }

                if ($this->nodeAlreadyPresentInListWithBetterOrSameRealCost($successor, $this->closedList)) {
                    continue;
                }

                $successor->setParent($currentNode);

                $this->closedList->remove($successor);

                $this->openList->add($successor);
            }
        }

        return $path;
    }

    /**
     * Sets the algorithm to its initial state
     */
    private function clear(): void
    {
        $this->openList->clear();
        $this->closedList->clear();
    }

    /**
     * @param Node $node
     * @return Node[]
     */
    private function generateAdjacentNodes(Node $node): iterable
    {
        $adjacentNodes = [];

        $adjacentStates = $this->domainLogic->getAdjacentNodes($node->getState());

        foreach ($adjacentStates as $state) {
            $adjacentNodes[] = new Node($state);
        }

        return $adjacentNodes;
    }

    private function calculateRealCost(Node $node, Node $adjacent): float | int
    {
        $state = $node->getState();
        $adjacentState = $adjacent->getState();

        return $this->domainLogic->calculateRealCost($state, $adjacentState);
    }

    private function calculateEstimatedCost(Node $start, Node $end): float | int
    {
        $startState = $start->getState();
        $endState = $end->getState();

        return $this->domainLogic->calculateEstimatedCost($startState, $endState);
    }

    /**
     * @param Node $node
     * @return TNode[]
     */
    private function generatePathFromStartNodeTo(Node $node): iterable
    {
        $path = [];

        $currentNode = $node;

        while ($currentNode !== null) {
            array_unshift($path, $currentNode->getState());

            $currentNode = $currentNode->getParent();
        }

        return $path;
    }

    /**
     * @param Node $node
     * @param Node $goal
     * @return Node[]
     */
    private function getAdjacentNodesWithTentativeScore(Node $node, Node $goal): iterable
    {
        $nodes = $this->generateAdjacentNodes($node);

        foreach ($nodes as $adjacentNode) {
            $adjacentNode->setG($node->getG() + $this->calculateRealCost($node, $adjacentNode));
            $adjacentNode->setH($this->calculateEstimatedCost($adjacentNode, $goal));
        }

        return $nodes;
    }

    private function nodeAlreadyPresentInListWithBetterOrSameRealCost(
        Node $node,
        NodeCollectionInterface $nodeList
    ): bool {
        if ($nodeList->contains($node)) {
            /** @var Node $nodeInList Cannot be null because the list contains it */
            $nodeInList = $nodeList->get($node->getId());

            if ($node->getG() >= $nodeInList->getG()) {
                return true;
            }
        }

        return false;
    }
}
