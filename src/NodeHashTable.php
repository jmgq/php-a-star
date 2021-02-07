<?php

namespace JMGQ\AStar;

class NodeHashTable implements \IteratorAggregate, NodeCollectionInterface
{
    /** @var Node[] */
    private array $nodes = [];

    /**
     * {@inheritdoc}
     */
    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function extractBest(): ?Node
    {
        $bestNode = null;

        foreach ($this->nodes as $node) {
            if ($bestNode === null || $node->getF() < $bestNode->getF()) {
                $bestNode = $node;
            }
        }

        if ($bestNode !== null) {
            $this->remove($bestNode);
        }

        return $bestNode;
    }

    public function get(Node $node): ?Node
    {
        return $this->nodes[$node->getId()] ?? null;
    }

    public function add(Node $node): void
    {
        $this->nodes[$node->getId()] = $node;
    }

    public function remove(Node $node): void
    {
        unset($this->nodes[$node->getId()]);
    }

    public function isEmpty(): bool
    {
        return empty($this->nodes);
    }

    public function contains(Node $node): bool
    {
        return isset($this->nodes[$node->getId()]);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->nodes = [];
    }
}
