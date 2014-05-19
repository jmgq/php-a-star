<?php

namespace JMGQ\AStar\Example;

class Example
{
    private $terrainCost;

    public function __construct(TerrainCost $terrainCost)
    {
        $this->terrainCost = $terrainCost;
    }

    public function printSolution(MyNode $start, MyNode $goal)
    {
        $aStar = new MyAStar($this->terrainCost);

        $solution = $aStar->run($start, $goal);

        $printer = new SequencePrinter($this->terrainCost, $solution);

        $printer->printSequence();
    }
}
