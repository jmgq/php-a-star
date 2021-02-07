#!/usr/bin/env php
<?php

namespace JMGQ\AStar\Example\Graph;

require __DIR__ . '/../../vendor/autoload.php';

use JMGQ\AStar\AStar;

$links = [
    new Link(new Coordinate(0, 0), new Coordinate(2, 5), 6.5),
    new Link(new Coordinate(0, 0), new Coordinate(6, 4), 23.75),
    new Link(new Coordinate(2, 5), new Coordinate(3, 3), 5),
    new Link(new Coordinate(3, 3), new Coordinate(6, 4), 3.2),
    new Link(new Coordinate(6, 4), new Coordinate(10, 10), 8),
];

$graph = new Graph($links);

$start = new Coordinate(0, 0);
$goal = new Coordinate(10, 10);

$domainLogic = new DomainLogic($graph);
$aStar = new AStar($domainLogic);

$solution = $aStar->run($start, $goal);

$printer = new SequencePrinter($graph, $solution);

$printer->printSequence();

echo "\n";
