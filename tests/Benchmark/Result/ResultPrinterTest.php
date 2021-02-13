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
    /** @var MockObject & StyleInterface */
    private MockObject | StyleInterface $output;
    /** @var string[] */
    private array $expectedHeaders;

    /**
     * @return mixed[][]
     */
    public function hasSolutionProvider(): array
    {
        return [
            ['numberOfSolutions' => 8, 'numberOfTerrains' => 8, 'hasSolution' => 'Yes'],
            ['numberOfSolutions' => 0, 'numberOfTerrains' => 8, 'hasSolution' => 'No'],
            ['numberOfSolutions' => 2, 'numberOfTerrains' => 8, 'hasSolution' => 'Sometimes'],
        ];
    }

    protected function setUp(): void
    {
        $this->output = $this->createMock(StyleInterface::class);

        $this->sut = new ResultPrinter($this->output);

        $this->expectedHeaders = ['Size', 'Avg Duration', 'Min Duration', 'Max Duration', 'Solved?'];
    }

    public function testShouldPrintTableHeaders(): void
    {
        $results = [];

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders);

        $this->sut->display($results);
    }

    public function testShouldPrintResult(): void
    {
        $result = $this->createMockAggregatedResult(5, 4, 2, 6, 10, 10);

        $results = [$result];

        $expectedRows = [
            ['5x5', '4ms', '2ms', '6ms', 'Yes'],
        ];

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders, $expectedRows);

        $this->sut->display($results);
    }

    public function testShouldOrderResultsBySize(): void
    {
        $result10x10 = $this->createMockAggregatedResult(10, 4, 2, 6, 10, 10);
        $result5x5 = $this->createMockAggregatedResult(5, 4, 2, 6, 10, 10);

        $results = [$result10x10, $result5x5];

        $expectedRows = [
            ['5x5', '4ms', '2ms', '6ms', 'Yes'],
            ['10x10', '4ms', '2ms', '6ms', 'Yes'],
        ];

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
        $result = $this->createMockAggregatedResult(5, 4, 2, 6, $numberOfSolutions, $numberOfTerrains);

        $results = [$result];

        $expectedRows = [
            ['5x5', '4ms', '2ms', '6ms', $expectedHasSolution],
        ];

        $this->output->expects($this->once())
            ->method('table')
            ->with($this->expectedHeaders, $expectedRows);

        $this->sut->display($results);
    }

    private function createMockAggregatedResult(
        int $size,
        int $averageDuration,
        int $minimumDuration,
        int $maximumDuration,
        int $numberOfSolutions,
        int $numberOfTerrains
    ): AggregatedResult {
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
