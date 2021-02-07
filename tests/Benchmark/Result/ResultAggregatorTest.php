<?php

namespace JMGQ\AStar\Tests\Benchmark\Result;

use JMGQ\AStar\Benchmark\Result\AggregatedResult;
use JMGQ\AStar\Benchmark\Result\Result;
use JMGQ\AStar\Benchmark\Result\ResultAggregator;
use PHPUnit\Framework\TestCase;

class ResultAggregatorTest extends TestCase
{
    private ResultAggregator $sut;

    protected function setUp(): void
    {
        $this->sut = new ResultAggregator();
    }

    public function testShouldGroupResultsBySize(): void
    {
        $result1 = $this->createMockResult(5, 4, true);
        $result2 = $this->createMockResult(10, 1234, true);
        $result3 = $this->createMockResult(5, 20, true);
        $result4 = $this->createMockResult(5, 1, true);

        $results = [$result1, $result2, $result3, $result4];

        $aggregatedResults = $this->sut->process($results);

        $this->assertCount(2, $aggregatedResults);
        $this->assertContainsSize(5, $aggregatedResults);
        $this->assertContainsSize(10, $aggregatedResults);
    }

    public function testShouldCalculateDurations(): void
    {
        $result1 = $this->createMockResult(5, 4, true);
        $result2 = $this->createMockResult(5, 20, true);
        $result3 = $this->createMockResult(5, 1, true);

        $results = [$result1, $result2, $result3];

        $aggregatedResults = $this->sut->process($results);

        $this->assertCount(1, $aggregatedResults);
        $aggregatedResult = $aggregatedResults[0];
        $this->assertSame(8, $aggregatedResult->getAverageDuration());
        $this->assertSame(1, $aggregatedResult->getMinimumDuration());
        $this->assertSame(20, $aggregatedResult->getMaximumDuration());
    }

    public function testShouldCalculateNumberOfSolutions(): void
    {
        $result1 = $this->createMockResult(5, 1, true);
        $result2 = $this->createMockResult(5, 1, false);
        $result3 = $this->createMockResult(5, 1, true);

        $results = [$result1, $result2, $result3];

        $aggregatedResults = $this->sut->process($results);

        $this->assertCount(1, $aggregatedResults);
        $aggregatedResult = $aggregatedResults[0];
        $this->assertSame(2, $aggregatedResult->getNumberOfSolutions());
    }

    public function testShouldCalculateNumberOfTerrains(): void
    {
        $result1 = $this->createMockResult(5, 1, true);
        $result2 = $this->createMockResult(5, 1, false);

        $results = [$result1, $result2];

        $aggregatedResults = $this->sut->process($results);

        $this->assertCount(1, $aggregatedResults);
        $aggregatedResult = $aggregatedResults[0];
        $this->assertSame(2, $aggregatedResult->getNumberOfTerrains());
    }

    /**
     * @param int $needle
     * @param AggregatedResult[] $haystack
     */
    private function assertContainsSize(int $needle, array $haystack): void
    {
        foreach ($haystack as $result) {
            if ($result->getSize() === $needle) {
                return;
            }
        }

        $this->fail(
            'Failed asserting that the array ' . print_r($haystack, true) .
            'contains a result with the specified size ' . print_r($needle, true)
        );
    }

    private function createMockResult(int $size, int $duration, bool $hasSolution): Result
    {
        $result = $this->createMock(Result::class);
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
