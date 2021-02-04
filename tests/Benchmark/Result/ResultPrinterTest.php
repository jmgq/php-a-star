<?php

namespace JMGQ\AStar\Tests\Benchmark\Result;

use JMGQ\AStar\Benchmark\Result\AggregatedResult;
use JMGQ\AStar\Benchmark\Result\ResultPrinter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Style\StyleInterface;

class ResultPrinterTest extends TestCase
{
    private ResultPrinter $sut;
    private MockObject|StyleInterface $output;
    private array $expectedHeaders;

    public function hasSolutionProvider(): array
    {
        return array(
            array('numberOfSolutions' => 8, 'numberOfTerrains' => 8, 'hasSolution' => 'Yes'),
            array('numberOfSolutions' => 0, 'numberOfTerrains' => 8, 'hasSolution' => 'No'),
            array('numberOfSolutions' => 2, 'numberOfTerrains' => 8, 'hasSolution' => 'Sometimes'),
        );
    }

    protected function setUp(): void
    {
        $this->output = $this->createMock(StyleInterface::class);

        $this->sut = new ResultPrinter($this->output);

        $this->expectedHeaders = array('Size', 'Avg Duration', 'Min Duration', 'Max Duration', 'Solved?');
    }

    public function testShouldPrintTableHeaders(): void
    {
        $results = array();

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders);

        $this->sut->display($results);
    }

    public function testShouldPrintResult(): void
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

    public function testShouldOrderResultsBySize(): void
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
    public function testShouldFormatHasSolution(
        int $numberOfSolutions,
        int $numberOfTerrains,
        string $expectedHasSolution
    ): void {
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
        int $size,
        int $averageDuration,
        int $minimumDuration,
        int $maximumDuration,
        int $numberOfSolutions,
        int $numberOfTerrains
    ): MockObject|AggregatedResult {
        $result = $this->createMock(AggregatedResult::class);
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
