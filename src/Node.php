<?php

namespace JMGQ\AStar;

/**
 * @internal
 */
class Node
{
    private mixed $state;
    private string $id;
    private ?Node $parent = null;
    private float | int $gScore;
    private float | int $hScore;

    /**
     * @param mixed $state The state refers to the actual user data that represents a node in the user's business logic.
     */
    public function __construct(mixed $state)
    {
        $this->state = $state;
        $this->id = serialize($state);
    }

    /**
     * @return mixed Returns the state, which is the user data that represents a node in the user's business logic.
     */
    public function getState(): mixed
    {
        return $this->state;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setParent(Node $parent): void
    {
        $this->parent = $parent;
    }

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
