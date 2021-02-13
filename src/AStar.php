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
    /** @var NodeCollectionInterface<TNode> | NodeHashTable<TNode> */
    private NodeCollectionInterface $openList;
    /** @var NodeCollectionInterface<TNode> | NodeHashTable<TNode> */
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
     * @return iterable<TNode>
     */
    public function run(mixed $start, mixed $goal): iterable
    {
        $startNode = new Node($start);
        $goalNode = new Node($goal);

        return $this->executeAlgorithm($startNode, $goalNode);
    }

    /**
     * @param Node<TNode> $start
     * @param Node<TNode> $goal
     * @return iterable<TNode>
     */
    private function executeAlgorithm(Node $start, Node $goal): iterable
    {
        $path = [];

        $this->clear();

        $start->setG(0);
        $start->setH($this->calculateEstimatedCost($start, $goal));

        $this->openList->add($start);

        while (!$this->openList->isEmpty()) {
            /** @var Node<TNode> $currentNode Cannot be null because the open list is not empty */
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
     * @param Node<TNode> $node
     * @return iterable<Node<TNode>>
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

    /**
     * @param Node<TNode> $node
     * @param Node<TNode> $adjacent
     * @return float | int
     */
    private function calculateRealCost(Node $node, Node $adjacent): float | int
    {
        $state = $node->getState();
        $adjacentState = $adjacent->getState();

        return $this->domainLogic->calculateRealCost($state, $adjacentState);
    }

    /**
     * @param Node<TNode> $start
     * @param Node<TNode> $end
     * @return float | int
     */
    private function calculateEstimatedCost(Node $start, Node $end): float | int
    {
        $startState = $start->getState();
        $endState = $end->getState();

        return $this->domainLogic->calculateEstimatedCost($startState, $endState);
    }

    /**
     * @param Node<TNode> $node
     * @return iterable<TNode>
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
     * @param Node<TNode> $node
     * @param Node<TNode> $goal
     * @return iterable<Node<TNode>>
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

    /**
     * @param Node<TNode> $node
     * @param NodeCollectionInterface<TNode> $nodeList
     * @return bool
     */
    private function nodeAlreadyPresentInListWithBetterOrSameRealCost(
        Node $node,
        NodeCollectionInterface $nodeList
    ): bool {
        if ($nodeList->contains($node)) {
            /** @var Node<TNode> $nodeInList Cannot be null because the list contains it */
            $nodeInList = $nodeList->get($node->getId());

            if ($node->getG() >= $nodeInList->getG()) {
                return true;
            }
        }

        return false;
    }
}
