<?php

namespace JMGQ\AStar\Example\Terrain;

class SequencePrinter
{
    private TerrainCost $terrainCost;
    /** @var iterable<Position> */
    private iterable $sequence;
    private string $emptyTileToken = '-';
    private int $tileSize = 3;
    private string $padToken = ' ';

    /**
     * @param TerrainCost $terrainCost
     * @param iterable<Position> $sequence
     */
    public function __construct(TerrainCost $terrainCost, iterable $sequence)
    {
        $this->terrainCost = $terrainCost;
        $this->sequence = $sequence;
    }

    public function getEmptyTileToken(): string
    {
        return $this->emptyTileToken;
    }

    public function setEmptyTileToken(string $emptyTileToken): void
    {
        $this->emptyTileToken = $emptyTileToken;
    }

    public function getTileSize(): int
    {
        return $this->tileSize;
    }

    public function setTileSize(int $tileSize): void
    {
        if ($tileSize < 1) {
            throw new \InvalidArgumentException("Invalid tile size: $tileSize");
        }

        $this->tileSize = $tileSize;
    }

    public function getPadToken(): string
    {
        return $this->padToken;
    }

    public function setPadToken(string $padToken): void
    {
        $this->padToken = $padToken;
    }

    public function printSequence(): void
    {
        $board = $this->generateEmptyBoard();

        $step = 1;
        foreach ($this->sequence as $position) {
            $board[$position->getRow()][$position->getColumn()] = $this->getTile((string) $step);

            $step++;
        }

        $stringBoard = [];

        foreach ($board as $row) {
            $stringBoard[] = implode('', $row);
        }

        echo implode("\n", $stringBoard);
    }

    /**
     * @return string[][]
     */
    private function generateEmptyBoard(): array
    {
        $emptyTile = $this->getTile($this->getEmptyTileToken());

        $emptyRow = array_fill(0, $this->terrainCost->getTotalColumns(), $emptyTile);

        $board = array_fill(0, $this->terrainCost->getTotalRows(), $emptyRow);

        return $board;
    }

    private function getTile(string $value): string
    {
        return str_pad($value, $this->getTileSize(), $this->getPadToken(), STR_PAD_LEFT);
    }
}
