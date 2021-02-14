<?php

namespace JMGQ\AStar;

use JMGQ\AStar\Node\Collection\NodeCollectionInterface;
use JMGQ\AStar\Node\Collection\NodeHashTable;
use JMGQ\AStar\Node\Node;

/**
 * @template TState
 */
class AStar
{
    /** @var DomainLogicInterface<TState> */
    private DomainLogicInterface $domainLogic;
    /** @var NodeCollectionInterface<TState> | NodeHashTable<TState> */
    private NodeCollectionInterface $openList;
    /** @var NodeCollectionInterface<TState> | NodeHashTable<TState> */
    private NodeCollectionInterface $closedList;

    /**
     * @param DomainLogicInterface<TState> $domainLogic
     */
    public function __construct(DomainLogicInterface $domainLogic)
    {
        $this->domainLogic = $domainLogic;
        $this->openList = new NodeHashTable();
        $this->closedList = new NodeHashTable();
    }

    /**
     * @param TState $start
     * @param TState $goal
     * @return iterable<TState>
     */
    public function run(mixed $start, mixed $goal): iterable
    {
        $startNode = new Node($start);
        $goalNode = new Node($goal);

        return $this->executeAlgorithm($startNode, $goalNode);
    }

    /**
     * @param Node<TState> $start
     * @param Node<TState> $goal
     * @return iterable<TState>
     */
    private function executeAlgorithm(Node $start, Node $goal): iterable
    {
        $path = [];

        $this->clear();

        $start->setG(0);
        $start->setH($this->calculateEstimatedCost($start, $goal));

        $this->openList->add($start);

        while (!$this->openList->isEmpty()) {
            /** @var Node<TState> $currentNode Cannot be null because the open list is not empty */
            $currentNode = $this->openList->extractBest();

            $this->closedList->add($currentNode);

            if ($currentNode->getId() === $goal->getId()) {
                $path = $this->generatePathFromStartNodeTo($currentNode);
                break;
            }

            $successors = $this->getAdjacentNodesWithTentativeScore($currentNode, $goal);

            $this->evaluateSuccessors($successors, $currentNode);
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
     * @param Node<TState> $node
     * @return iterable<Node<TState>>
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
     * @param Node<TState> $node
     * @param Node<TState> $adjacent
     * @return float | int
     */
    private function calculateRealCost(Node $node, Node $adjacent): float | int
    {
        $state = $node->getState();
        $adjacentState = $adjacent->getState();

        return $this->domainLogic->calculateRealCost($state, $adjacentState);
    }

    /**
     * @param Node<TState> $start
     * @param Node<TState> $end
     * @return float | int
     */
    private function calculateEstimatedCost(Node $start, Node $end): float | int
    {
        $startState = $start->getState();
        $endState = $end->getState();

        return $this->domainLogic->calculateEstimatedCost($startState, $endState);
    }

    /**
     * @param Node<TState> $node
     * @return iterable<TState>
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
     * @param Node<TState> $node
     * @param Node<TState> $goal
     * @return iterable<Node<TState>>
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
     * @param iterable<Node<TState>> $successors
     * @param Node<TState> $parent
     */
    private function evaluateSuccessors(iterable $successors, Node $parent): void
    {
        foreach ($successors as $successor) {
            if ($this->nodeAlreadyPresentInListWithBetterOrSameRealCost($successor, $this->openList)) {
                continue;
            }

            if ($this->nodeAlreadyPresentInListWithBetterOrSameRealCost($successor, $this->closedList)) {
                continue;
            }

            $successor->setParent($parent);

            $this->closedList->remove($successor);

            $this->openList->add($successor);
        }
    }

    /**
     * @param Node<TState> $node
     * @param NodeCollectionInterface<TState> $nodeList
     * @return bool
     */
    private function nodeAlreadyPresentInListWithBetterOrSameRealCost(
        Node $node,
        NodeCollectionInterface $nodeList
    ): bool {
        if ($nodeList->contains($node)) {
            /** @var Node<TState> $nodeInList Cannot be null because the list contains it */
            $nodeInList = $nodeList->get($node->getId());

            if ($node->getG() >= $nodeInList->getG()) {
                return true;
            }
        }

        return false;
    }
}
