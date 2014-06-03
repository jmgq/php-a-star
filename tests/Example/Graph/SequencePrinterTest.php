<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\Graph;
use JMGQ\AStar\Example\Graph\Link;
use JMGQ\AStar\Example\Graph\MyNode;
use JMGQ\AStar\Example\Graph\SequencePrinter;

class SequencePrinterTest extends \PHPUnit_Framework_TestCase
{
    /** @var Graph */
    private $graph;

    public function setUp()
    {
        $links = array(
            new Link(new MyNode(0, 0), new MyNode(2, 5), 6.5),
            new Link(new MyNode(0, 0), new MyNode(6, 4), 23.75),
            new Link(new MyNode(2, 5), new MyNode(3, 3), 5),
            new Link(new MyNode(3, 3), new MyNode(6, 4), 3.2),
            new Link(new MyNode(6, 4), new MyNode(10, 10), 8)
        );

        $this->graph = new Graph($links);
    }

    public function testShouldPrintANodeSequence()
    {
        $sequence = array(
            new MyNode(0, 0),
            new MyNode(2, 5),
            new MyNode(3, 3),
            new MyNode(6, 4),
            new MyNode(10, 10)
        );

        $expectedOutput = "(0, 0) => (2, 5) => (3, 3) => (6, 4) => (10, 10)\nTotal cost: 22.7";

        $sut = new SequencePrinter($this->graph, $sequence);

        $sut->printSequence();

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintMessageIfSequenceIsEmpty()
    {
        $expectedOutput = "Total cost: 0";

        $sut = new SequencePrinter($this->graph, array());

        $sut->printSequence();

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSequenceEvenIfItOnlyHasOneNode()
    {
        $sequence = array(new MyNode(3, 3));

        $expectedOutput = "(3, 3)\nTotal cost: 0";

        $sut = new SequencePrinter($this->graph, $sequence);

        $sut->printSequence();

        $this->expectOutputString($expectedOutput);
    }
}
