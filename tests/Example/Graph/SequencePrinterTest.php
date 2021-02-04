<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\Graph;
use JMGQ\AStar\Example\Graph\Link;
use JMGQ\AStar\Example\Graph\MyNode;
use JMGQ\AStar\Example\Graph\SequencePrinter;
use PHPUnit\Framework\TestCase;

class SequencePrinterTest extends TestCase
{
    private Graph $graph;

    protected function setUp(): void
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

    public function testShouldPrintANodeSequence(): void
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

    public function testShouldPrintMessageIfSequenceIsEmpty(): void
    {
        $expectedOutput = "Total cost: 0";

        $sut = new SequencePrinter($this->graph, array());

        $sut->printSequence();

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSequenceEvenIfItOnlyHasOneNode(): void
    {
        $sequence = array(new MyNode(3, 3));

        $expectedOutput = "(3, 3)\nTotal cost: 0";

        $sut = new SequencePrinter($this->graph, $sequence);

        $sut->printSequence();

        $this->expectOutputString($expectedOutput);
    }
}
