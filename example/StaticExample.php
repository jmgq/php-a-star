<?php

namespace JMGQ\AStar\Example;

class StaticExample
{
    public static function printSolution(TerrainCost $terrainCost, MyNode $start, MyNode $goal)
    {
        $aStar = new MyAStar($terrainCost);

        $solution = $aStar->run($start, $goal);

        $printer = new SequencePrinter($terrainCost, $solution);

        $printer->printSequence();
    }
}
