<?php

namespace JMGQ\AStar\Node;

/**
 * @template TState
 * @internal
 */
class Node
{
    /** @var TState */
    private mixed $state;
    private string $id;
    /** @var Node<TState> | null  */
    private ?Node $parent = null;
    /** @psalm-suppress PropertyNotSetInConstructor Reading G should fail visibly if it hasn't been previously set */
    private float | int $gScore;
    /** @psalm-suppress PropertyNotSetInConstructor Reading H should fail visibly if it hasn't been previously set */
    private float | int $hScore;

    /**
     * @param TState $state It refers to the actual user data that represents a node in the user's business logic.
     */
    public function __construct(mixed $state)
    {
        $this->state = $state;
        $this->id = $state instanceof NodeIdentifierInterface ? $state->getUniqueNodeId() : serialize($state);
    }

    /**
     * @return TState Returns the state, which is the user data that represents a node in the user's business logic.
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
     * @param Node<TState> $parent
     */
    public function setParent(Node $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Node<TState> | null
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
