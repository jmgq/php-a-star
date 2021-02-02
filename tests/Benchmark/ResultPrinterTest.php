<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\ResultPrinter;

class ResultPrinterTest extends \PHPUnit_Framework_TestCase
{
    private $sut;
    private $output;
    private $expectedHeaders;

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
        $result = $this->getMockResult(5, 1234, true);

        $results = array($result);

        $expectedRows = array(
            array('5x5', '1234ms', '1234ms', '1234ms', 'Yes'),
        );

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders, $expectedRows);

        $this->sut->display($results);
    }

    public function testShouldOrderResultsBySize()
    {
        $result10x10 = $this->getMockResult(10, 1234, true);
        $result5x5 = $this->getMockResult(5, 1234, true);

        $results = array($result10x10, $result5x5);

        $expectedRows = array(
            array('5x5', '1234ms', '1234ms', '1234ms', 'Yes'),
            array('10x10', '1234ms', '1234ms', '1234ms', 'Yes'),
        );

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders, $expectedRows);

        $this->sut->display($results);
    }

    public function testShouldGroupResultsBySize()
    {
        $result1 = $this->getMockResult(5, 4, true);
        $result2 = $this->getMockResult(10, 1234, true);
        $result3 = $this->getMockResult(5, 20, true);
        $result4 = $this->getMockResult(5, 1, true);

        $results = array($result1, $result2, $result3, $result4);

        $expectedRows = array(
            array('5x5', '8ms', '1ms', '20ms', 'Yes'),
            array('10x10', '1234ms', '1234ms', '1234ms', 'Yes'),
        );

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders, $expectedRows);

        $this->sut->display($results);
    }

    public function testShouldPrintWhenItHasSolution()
    {
        $hasSolution = true;

        $result = $this->getMockResult(5, 1234, $hasSolution);

        $results = array($result, $result, $result, $result);

        $expectedHasSolution = 'Yes';

        $expectedRows = array(
            array('5x5', '1234ms', '1234ms', '1234ms', $expectedHasSolution),
        );

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders, $expectedRows);

        $this->sut->display($results);
    }

    public function testShouldPrintWhenItDoesNotHaveSolution()
    {
        $hasSolution = false;

        $result = $this->getMockResult(5, 1234, $hasSolution);

        $results = array($result, $result, $result, $result);

        $expectedHasSolution = 'No';

        $expectedRows = array(
            array('5x5', '1234ms', '1234ms', '1234ms', $expectedHasSolution),
        );

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders, $expectedRows);

        $this->sut->display($results);
    }

    public function testShouldNotifyWhenTheSolutionValueIsInconsistent()
    {
        $resultWithSolution = $this->getMockResult(5, 1234, true);
        $resultWithoutSolution = $this->getMockResult(5, 1234, false);

        $results = array($resultWithSolution, $resultWithoutSolution);

        $expectedHasSolution = 'Sometimes';

        $expectedRows = array(
            array('5x5', '1234ms', '1234ms', '1234ms', $expectedHasSolution),
        );

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders, $expectedRows);

        $this->sut->display($results);
    }

    private function getMockResult($size, $duration, $hasSolution)
    {
        $result = $this->getMockBuilder('JMGQ\AStar\Benchmark\Result')
            ->disableOriginalConstructor()
            ->getMock();
        $result->expects($this->atLeastOnce())
            ->method('getSize')
            ->willReturn($size);
        $result->expects($this->atLeastOnce())
            ->method('getDuration')
            ->willReturn($duration);
        $result->expects($this->atLeastOnce())
            ->method('hasSolution')
            ->willReturn($hasSolution);

        return $result;
    }
}
