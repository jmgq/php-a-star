<?php

namespace JMGQ\AStar;

class AStar
{
    private DomainLogicInterface $domainLogic;
    private NodeCollectionInterface $openList;
    private NodeCollectionInterface $closedList;

    public function __construct(DomainLogicInterface $domainLogic)
    {
        $this->domainLogic = $domainLogic;
        $this->openList = new NodeHashTable();
        $this->closedList = new NodeHashTable();
    }

    /**
     * @param mixed $start
     * @param mixed $goal
     * @return mixed[]
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
     * @return mixed[]
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

            $successors = $this->computeAdjacentNodes($currentNode, $goal);

            foreach ($successors as $successor) {
                if ($this->openList->contains($successor)) {
                    /** @var Node $successorInOpenList Cannot be null because the open list contains it */
                    $successorInOpenList = $this->openList->get($successor->getId());

                    if ($successor->getG() >= $successorInOpenList->getG()) {
                        continue;
                    }
                }

                if ($this->closedList->contains($successor)) {
                    /** @var Node $successorInClosedList Cannot be null because the closed list contains it */
                    $successorInClosedList = $this->closedList->get($successor->getId());

                    if ($successor->getG() >= $successorInClosedList->getG()) {
                        continue;
                    }
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
     * @return mixed[]
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
    private function computeAdjacentNodes(Node $node, Node $goal): iterable
    {
        $nodes = $this->generateAdjacentNodes($node);

        foreach ($nodes as $adjacentNode) {
            $adjacentNode->setG($node->getG() + $this->calculateRealCost($node, $adjacentNode));
            $adjacentNode->setH($this->calculateEstimatedCost($adjacentNode, $goal));
        }

        return $nodes;
    }
}
