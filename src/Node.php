<?php

namespace JMGQ\AStar;

/**
 * @internal
 */
class Node
{
    private mixed $userData;
    private string $id;
    private ?Node $parent = null;
    private float | int $gScore;
    private float | int $hScore;

    public function __construct(mixed $userData)
    {
        $this->userData = $userData;
        $this->id = serialize($userData);
    }

    public function getUserData(): mixed
    {
        return $this->userData;
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
