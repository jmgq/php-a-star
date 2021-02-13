<?php

namespace JMGQ\AStar\Benchmark\Result;

class ResultAggregator
{
    /**
     * @param Result[] $results
     * @return AggregatedResult[]
     */
    public function process(array $results): array
    {
        $aggregatedResults = [];

        $sizeToResultsMap = $this->groupBySize($results);

        foreach ($sizeToResultsMap as $size => $groupedResults) {
            /** @var non-empty-array<int> $durations */
            $durations = $this->getDurations($groupedResults);
            $averageDuration = $this->averageDuration($durations);
            $minimumDuration = min($durations);
            $maximumDuration = max($durations);
            $numberOfSolutions = $this->getNumberOfResultsWithASolution($groupedResults);
            $numberOfTerrains = count($groupedResults);

            $aggregatedResults[] = new AggregatedResult(
                $size,
                $averageDuration,
                $minimumDuration,
                $maximumDuration,
                $numberOfSolutions,
                $numberOfTerrains
            );
        }

        return $aggregatedResults;
    }

    /**
     * @param Result[] $results
     * @return array<int, Result[]>
     */
    private function groupBySize(array $results): array
    {
        $groupedResults = [];

        foreach ($results as $result) {
            $groupedResults[$result->getSize()][] = $result;
        }

        return $groupedResults;
    }

    /**
     * @param Result[] $results
     * @return int[]
     */
    private function getDurations(array $results): array
    {
        return array_map(static fn ($result) => $result->getDuration(), $results);
    }

    /**
     * @param int[] $durations
     * @return int
     */
    private function averageDuration(array $durations): int
    {
        return (int) (array_sum($durations) / count($durations));
    }

    /**
     * @param Result[] $results
     * @return int
     */
    private function getNumberOfResultsWithASolution(array $results): int
    {
        $solvedResults = array_filter($results, static fn ($result) => $result->hasSolution());

        return count($solvedResults);
    }
}
