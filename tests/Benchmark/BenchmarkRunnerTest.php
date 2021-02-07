<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\BenchmarkRunner;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\ProgressBar;

class BenchmarkRunnerTest extends TestCase
{
    private BenchmarkRunner $sut;
    private ProgressBar | MockObject $progressBar;

    protected function setUp(): void
    {
        $this->progressBar = $this->createMock(ProgressBar::class);

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
