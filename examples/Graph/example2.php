<?php

namespace JMGQ\AStar\Example\Graph;

require __DIR__ . '/../../vendor/autoload.php';

/*
This example hangs on v1.1.0.
fix_parent_loop patch to Algorithm.php causes this example to work.
    -- robin@pathwayi.com
*/

$nodes=array(
	"nodestart" => new MyNode(0, 0),
	"node1" => new MyNode(2, 5),
	"nodegoal" => new MyNode(6, 4)
);


$links = array(

//    new Link($nodes["nodestart"] , $nodes["nodegoal"], 6 ),
//    new Link($nodes["nodegoal"] , $nodes["nodestart"], 6 ),

    new Link($nodes["nodestart"] , $nodes["node1"], 6 ),
    new Link($nodes["node1"] , $nodes["nodestart"], 6 ),

    new Link($nodes["node1"] , $nodes["nodegoal"], 23 ),
   new Link($nodes["nodegoal"] , $nodes["node1"], 23 )
);
$graph = new Graph($links);

$start = $nodes["nodestart"];
$goal = $nodes["nodegoal"];

$aStar = new MyAStar($graph);
$solution = $aStar->run($start, $goal);


$printer = new SequencePrinter($graph, $solution);
$printer->printSequence();

echo "\n";
