<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\Coordinate;
use JMGQ\AStar\Example\Graph\Graph;
use JMGQ\AStar\Example\Graph\Link;
use JMGQ\AStar\Example\Graph\SequencePrinter;
use PHPUnit\Framework\TestCase;

class SequencePrinterTest extends TestCase
{
    private Graph $graph;

    protected function setUp(): void
    {
        $links = [
            new Link(new Coordinate(0, 0), new Coordinate(2, 5), 6.5),
            new Link(new Coordinate(0, 0), new Coordinate(6, 4), 23.75),
            new Link(new Coordinate(2, 5), new Coordinate(3, 3), 5),
            new Link(new Coordinate(3, 3), new Coordinate(6, 4), 3.2),
            new Link(new Coordinate(6, 4), new Coordinate(10, 10), 8),
        ];

        $this->graph = new Graph($links);
    }

    public function testShouldPrintANodeSequence(): void
    {
        $sequence = [
            new Coordinate(0, 0),
            new Coordinate(2, 5),
            new Coordinate(3, 3),
            new Coordinate(6, 4),
            new Coordinate(10, 10)
        ];

        $expectedOutput = "(0, 0) => (2, 5) => (3, 3) => (6, 4) => (10, 10)\nTotal cost: 22.7";

        $sut = new SequencePrinter($this->graph, $sequence);

        $sut->printSequence();

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintMessageIfSequenceIsEmpty(): void
    {
        $expectedOutput = 'Total cost: 0';

        $sut = new SequencePrinter($this->graph, []);

        $sut->printSequence();

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSequenceEvenIfItOnlyHasOneNode(): void
    {
        $sequence = [new Coordinate(3, 3)];

        $expectedOutput = "(3, 3)\nTotal cost: 0";

        $sut = new SequencePrinter($this->graph, $sequence);

        $sut->printSequence();

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldThrowExceptionIfTheSequenceIsNotConnected(): void
    {
        $sequence = [
            new Coordinate(0, 0),
            new Coordinate(2, 5),
            new Coordinate(99999, 99999),
        ];

        $sut = new SequencePrinter($this->graph, $sequence);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Some of the nodes in the provided sequence are not connected');
        $this->expectOutputString('');

        $sut->printSequence();
    }
}
