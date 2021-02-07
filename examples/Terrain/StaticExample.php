<?php

namespace JMGQ\AStar\Example\Terrain;

use JMGQ\AStar\AStar;

class StaticExample
{
    public static function printSolution(TerrainCost $terrainCost, Position $start, Position $goal): void
    {
        $domainLogic = new DomainLogic($terrainCost);
        $aStar = new AStar($domainLogic);

        $solution = $aStar->run($start, $goal);

        $printer = new SequencePrinter($terrainCost, $solution);

        $printer->printSequence();
    }
}
