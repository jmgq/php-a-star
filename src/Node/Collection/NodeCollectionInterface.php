<?php

namespace JMGQ\AStar\Node\Collection;

use JMGQ\AStar\Node\Node;

/**
 * @template TState
 * @extends \Traversable<mixed, Node<TState>>
 * @internal
 */
interface NodeCollectionInterface extends \Traversable
{
    /**
     * Obtains the node with the lowest F score. It also removes it from the collection.
     *
     * @return Node<TState> | null
     */
    public function extractBest(): ?Node;

    /**
     * @param string $nodeId
     * @return Node<TState> | null
     */
    public function get(string $nodeId): ?Node;

    /**
     * @param Node<TState> $node
     */
    public function add(Node $node): void;

    /**
     * @param Node<TState> $node
     */
    public function remove(Node $node): void;

    public function isEmpty(): bool;

    /**
     * @param Node<TState> $node
     * @return bool
     */
    public function contains(Node $node): bool;

    /**
     * Empties the collection
     */
    public function clear(): void;
}
