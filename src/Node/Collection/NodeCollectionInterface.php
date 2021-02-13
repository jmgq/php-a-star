<?php

namespace JMGQ\AStar\Node\Collection;

use JMGQ\AStar\Node\Node;

/**
 * @template TNode
 * @extends \Traversable<mixed, Node<TNode>>
 */
interface NodeCollectionInterface extends \Traversable
{
    /**
     * Obtains the node with the lowest F score. It also removes it from the collection.
     *
     * @return Node<TNode> | null
     */
    public function extractBest(): ?Node;

    /**
     * @param string $nodeId
     * @return Node<TNode> | null
     */
    public function get(string $nodeId): ?Node;

    /**
     * @param Node<TNode> $node
     */
    public function add(Node $node): void;

    /**
     * @param Node<TNode> $node
     */
    public function remove(Node $node): void;

    public function isEmpty(): bool;

    /**
     * @param Node<TNode> $node
     * @return bool
     */
    public function contains(Node $node): bool;

    /**
     * Empties the collection
     */
    public function clear(): void;
}
