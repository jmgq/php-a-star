<?php

namespace JMGQ\AStar\Example\Terrain;

class SequencePrinter
{
    private $terrainCost;
    private $sequence;
    private $emptyTileToken = '-';
    private $tileSize = 3;
    private $padToken = ' ';

    /**
     * @param TerrainCost $terrainCost
     * @param MyNode[] $sequence
     */
    public function __construct(TerrainCost $terrainCost, array $sequence)
    {
        $this->terrainCost = $terrainCost;
        $this->sequence = $sequence;
    }

    /**
     * @return string
     */
    public function getEmptyTileToken()
    {
        return $this->emptyTileToken;
    }

    /**
     * @param string $emptyTileToken
     */
    public function setEmptyTileToken($emptyTileToken)
    {
        if (!is_string($emptyTileToken)) {
            throw new \InvalidArgumentException('Invalid empty tile token: ' . print_r($emptyTileToken, true));
        }

        $this->emptyTileToken = $emptyTileToken;
    }

    /**
     * @return int
     */
    public function getTileSize()
    {
        return $this->tileSize;
    }

    /**
     * @param int $tileSize
     */
    public function setTileSize($tileSize)
    {
        $naturalNumber = filter_var($tileSize, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

        if ($naturalNumber === false) {
            throw new \InvalidArgumentException('Invalid tile size: ' . print_r($tileSize, true));
        }

        $this->tileSize = $naturalNumber;
    }

    /**
     * @return string
     */
    public function getPadToken()
    {
        return $this->padToken;
    }

    /**
     * @param string $padToken
     */
    public function setPadToken($padToken)
    {
        if (!is_string($padToken)) {
            throw new \InvalidArgumentException('Invalid pad token: ' . print_r($padToken, true));
        }

        $this->padToken = $padToken;
    }

    public function printSequence()
    {
        $board = $this->generateEmptyBoard();

        $step = 1;
        foreach ($this->sequence as $node) {
            $board[$node->getRow()][$node->getColumn()] = $this->getTile($step);

            $step++;
        }

        $stringBoard = array();

        foreach ($board as $row) {
            $stringBoard[] = implode('', $row);
        }

        echo implode("\n", $stringBoard);
    }

    private function generateEmptyBoard()
    {
        $emptyTile = $this->getTile($this->getEmptyTileToken());

        $emptyRow = array_fill(0, $this->terrainCost->getTotalColumns(), $emptyTile);

        $board = array_fill(0, $this->terrainCost->getTotalRows(), $emptyRow);

        return $board;
    }

    private function getTile($value)
    {
        return str_pad($value, $this->getTileSize(), $this->getPadToken(), STR_PAD_LEFT);
    }
}
