<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\BenchmarkRunner;
use JMGQ\AStar\Benchmark\ProgressBarInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BenchmarkRunnerTest extends TestCase
{
    private BenchmarkRunner $sut;
    /** @var MockObject & ProgressBarInterface */
    private MockObject | ProgressBarInterface $progressBar;

    protected function setUp(): void
    {
        $this->progressBar = $this->createMock(ProgressBarInterface::class);

        $this->sut = new BenchmarkRunner($this->progressBar);
    }

    public function testShouldRunTheBenchmark(): void
    {
        $sizes = [1, 2, 3];
        $iterations = 2;
        $seed = null;

        $expectedSteps = $expectedResults = 6;

        $this->progressBar->expects($this->once())
            ->method('start')
            ->with($expectedSteps);

        $this->progressBar->expects($this->exactly($expectedSteps))
            ->method('advance');

        $this->progressBar->expects($this->once())
            ->method('finish');

        $results = $this->sut->run($sizes, $iterations, $seed);

        $this->assertCount($expectedResults, $results);
    }
}
