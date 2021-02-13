<?php

namespace JMGQ\AStar\Node;

/**
 * @template TNode
 * @internal
 */
class Node
{
    /** @var TNode */
    private mixed $state;
    private string $id;
    /** @var Node<TNode> | null  */
    private ?Node $parent = null;
    /** @psalm-suppress PropertyNotSetInConstructor Reading G should fail visibly if it hasn't been previously set */
    private float | int $gScore;
    /** @psalm-suppress PropertyNotSetInConstructor Reading H should fail visibly if it hasn't been previously set */
    private float | int $hScore;

    /**
     * @param TNode $state The state refers to the actual user data that represents a node in the user's business logic.
     */
    public function __construct(mixed $state)
    {
        $this->state = $state;
        $this->id = $state instanceof NodeIdentifierInterface ? $state->getUniqueNodeId() : serialize($state);
    }

    /**
     * @return TNode Returns the state, which is the user data that represents a node in the user's business logic.
     */
    public function getState(): mixed
    {
        return $this->state;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param Node<TNode> $parent
     */
    public function setParent(Node $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Node<TNode> | null
     */
    public function getParent(): ?Node
    {
        return $this->parent;
    }

    public function getF(): float | int
    {
        return $this->getG() + $this->getH();
    }

    public function setG(float | int $score): void
    {
        $this->gScore = $score;
    }

    public function getG(): float | int
    {
        return $this->gScore;
    }

    public function setH(float | int $score): void
    {
        $this->hScore = $score;
    }

    public function getH(): float | int
    {
        return $this->hScore;
    }
}
