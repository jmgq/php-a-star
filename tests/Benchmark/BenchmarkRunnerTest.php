<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\BenchmarkRunner;

class BenchmarkRunnerTest extends \PHPUnit_Framework_TestCase
{
    private $sut;
    private $progressBar;

    protected function setUp()
    {
        $this->progressBar = $this->getMockBuilder('Symfony\Component\Console\Helper\ProgressBar')
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new BenchmarkRunner($this->progressBar);
    }

    public function testShouldRunTheBenchmark()
    {
        $sizes = array(1, 2, 3);
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
