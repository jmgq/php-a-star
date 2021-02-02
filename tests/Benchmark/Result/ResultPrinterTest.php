<?php

namespace JMGQ\AStar\Tests\Benchmark\Result;

use JMGQ\AStar\Benchmark\Result\ResultPrinter;

class ResultPrinterTest extends \PHPUnit_Framework_TestCase
{
    private $sut;
    private $output;
    private $expectedHeaders;

    public function hasSolutionProvider()
    {
        return array(
            array('numberOfSolutions' => 8, 'numberOfTerrains' => 8, 'hasSolution' => 'Yes'),
            array('numberOfSolutions' => 0, 'numberOfTerrains' => 8, 'hasSolution' => 'No'),
            array('numberOfSolutions' => 2, 'numberOfTerrains' => 8, 'hasSolution' => 'Sometimes'),
        );
    }

    protected function setUp()
    {
        $this->output = $this->getMock('Symfony\Component\Console\Style\StyleInterface');

        $this->sut = new ResultPrinter($this->output);

        $this->expectedHeaders = array('Size', 'Avg Duration', 'Min Duration', 'Max Duration', 'Solved?');
    }

    public function testShouldPrintTableHeaders()
    {
        $results = array();

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders);

        $this->sut->display($results);
    }

    public function testShouldPrintResult()
    {
        $result = $this->getMockAggregatedResult(5, 4, 2, 6, 10, 10);

        $results = array($result);

        $expectedRows = array(
            array('5x5', '4ms', '2ms', '6ms', 'Yes'),
        );

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders, $expectedRows);

        $this->sut->display($results);
    }

    public function testShouldOrderResultsBySize()
    {
        $result10x10 = $this->getMockAggregatedResult(10, 4, 2, 6, 10, 10);
        $result5x5 = $this->getMockAggregatedResult(5, 4, 2, 6, 10, 10);

        $results = array($result10x10, $result5x5);

        $expectedRows = array(
            array('5x5', '4ms', '2ms', '6ms', 'Yes'),
            array('10x10', '4ms', '2ms', '6ms', 'Yes'),
        );

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders, $expectedRows);

        $this->sut->display($results);
    }

    /**
     * @dataProvider hasSolutionProvider
     */
    public function testShouldFormatHasSolution($numberOfSolutions, $numberOfTerrains, $expectedHasSolution)
    {
        $result = $this->getMockAggregatedResult(5, 4, 2, 6, $numberOfSolutions, $numberOfTerrains);

        $results = array($result);

        $expectedRows = array(
            array('5x5', '4ms', '2ms', '6ms', $expectedHasSolution),
        );

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders, $expectedRows);

        $this->sut->display($results);
    }

    private function getMockAggregatedResult(
        $size,
        $averageDuration,
        $minimumDuration,
        $maximumDuration,
        $numberOfSolutions,
        $numberOfTerrains
    ) {
        $result = $this->getMockBuilder('JMGQ\AStar\Benchmark\Result\AggregatedResult')
            ->disableOriginalConstructor()
            ->getMock();
        $result->expects($this->atLeastOnce())
            ->method('getSize')
            ->willReturn($size);
        $result->expects($this->atLeastOnce())
            ->method('getAverageDuration')
            ->willReturn($averageDuration);
        $result->expects($this->atLeastOnce())
            ->method('getMinimumDuration')
            ->willReturn($minimumDuration);
        $result->expects($this->atLeastOnce())
            ->method('getMaximumDuration')
            ->willReturn($maximumDuration);
        $result->expects($this->atLeastOnce())
            ->method('getNumberOfSolutions')
            ->willReturn($numberOfSolutions);
        $result->expects($this->atLeastOnce())
            ->method('getNumberOfTerrains')
            ->willReturn($numberOfTerrains);

        return $result;
    }
}
