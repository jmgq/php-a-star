<?php

namespace JMGQ\AStar\Example;

require __DIR__ . '/../vendor/autoload.php';

$terrainCost = new TerrainCost(
    array(
        array(3, 2, 3, 6, 1),
        array(1, 3, 4, 1, 1),
        array(3, 1, 1, 4, 1),
        array(1, 1, 5, 2, 1)
    )
);

$start = new MyNode(0, 0);
$goal = new MyNode(0, 4);

$aStar = new MyAStar($terrainCost);

$solution = $aStar->run($start, $goal);

$printer = new SequencePrinter($terrainCost, $solution);

$printer->printSequence();
