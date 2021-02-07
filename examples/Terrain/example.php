#!/usr/bin/env php
<?php

namespace JMGQ\AStar\Example\Terrain;

require __DIR__ . '/../../vendor/autoload.php';

use JMGQ\AStar\AStar;

$terrainCost = new TerrainCost([
    [3, 2, 3, 6, 1],
    [1, 3, 4, 1, 1],
    [3, 1, 1, 4, 1],
    [1, 1, 5, 2, 1]
]);

$start = new Position(0, 0);
$goal = new Position(0, 4);

$domainLogic = new DomainLogic($terrainCost);
$aStar = new AStar($domainLogic);

$solution = $aStar->run($start, $goal);

$printer = new SequencePrinter($terrainCost, $solution);

$printer->printSequence();

echo "\n";
