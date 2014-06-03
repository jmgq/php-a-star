<?php

namespace JMGQ\AStar\Example\Graph;

require __DIR__ . '/../../vendor/autoload.php';

$links = array(
    new Link(new MyNode(0, 0), new MyNode(2, 5), 6.5),
    new Link(new MyNode(0, 0), new MyNode(6, 4), 23.75),
    new Link(new MyNode(2, 5), new MyNode(3, 3), 5),
    new Link(new MyNode(3, 3), new MyNode(6, 4), 3.2),
    new Link(new MyNode(6, 4), new MyNode(10, 10), 8)
);

$graph = new Graph($links);

$start = new MyNode(0, 0);
$goal = new MyNode(10, 10);

$aStar = new MyAStar($graph);

$solution = $aStar->run($start, $goal);

$printer = new SequencePrinter($graph, $solution);

$printer->printSequence();

echo "\n";
