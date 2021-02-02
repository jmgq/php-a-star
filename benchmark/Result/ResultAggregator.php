<?php

namespace JMGQ\AStar\Benchmark\Result;

class ResultAggregator
{
    /**
     * @param Result[] $results
     * @return AggregatedResult[]
     */
    public function process(array $results)
    {
        $aggregatedResults = array();

        $sizeToResultsMap = $this->groupBySize($results);

        foreach ($sizeToResultsMap as $size => $groupedResults) {
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
     * @return Result[][]
     */
    private function groupBySize(array $results)
    {
        $groupedResults = array();

        foreach ($results as $result) {
            $groupedResults[$result->getSize()][] = $result;
        }

        return $groupedResults;
    }

    /**
     * @param Result[] $results
     * @return int[]
     */
    private function getDurations(array $results)
    {
        return array_map(function ($result) {
            return $result->getDuration();
        }, $results);
    }

    /**
     * @param int[] $durations
     * @return int
     */
    private function averageDuration(array $durations)
    {
        return (int) (array_sum($durations) / count($durations));
    }

    /**
     * @param Result[] $results
     * @return int
     */
    private function getNumberOfResultsWithASolution(array $results)
    {
        $solvedResults = array_filter($results, function ($result) {
            return $result->hasSolution();
        });

        return count($solvedResults);
    }
}
