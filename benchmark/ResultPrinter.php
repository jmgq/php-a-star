<?php

namespace JMGQ\AStar\Benchmark;

use Symfony\Component\Console\Style\StyleInterface;

class ResultPrinter
{
    private $output;

    public function __construct(StyleInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param Result[] $results
     */
    public function display($results)
    {
        $tableRows = array();

        $orderedResults = $this->orderResults($results);
        $sizeToResultsMap = $this->groupBySize($orderedResults);

        foreach ($sizeToResultsMap as $size => $groupedResults) {
            $formattedSize = $size . 'x' . $size;
            $durations = $this->getDurations($groupedResults);
            $averageDuration = $this->averageDuration($durations) . 'ms';
            $minimumDuration = min($durations) . 'ms';
            $maximumDuration = max($durations) . 'ms';
            $solutionFound = $this->formatSolutionFound($groupedResults);

            $tableRows[] = array($formattedSize, $averageDuration, $minimumDuration, $maximumDuration, $solutionFound);
        }

        $tableHeaders = array('Size', 'Avg Duration', 'Min Duration', 'Max Duration', 'Solved?');

        $this->output->table($tableHeaders, $tableRows);
    }

    /**
     * @param Result[] $results
     * @return Result[]
     */
    private function orderResults($results)
    {
        usort($results, function ($a, $b) {
            return $a->getSize() > $b->getSize();
        });

        return $results;
    }

    /**
     * @param Result[] $results
     * @return Result[][]
     */
    private function groupBySize($results)
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
    private function getDurations($results)
    {
        return array_map(function ($result) {
            return $result->getDuration();
        }, $results);
    }

    /**
     * @param int[] $durations
     * @return int
     */
    private function averageDuration($durations)
    {
        return (int) (array_sum($durations) / count($durations));
    }

    /**
     * @param Result[] $results
     * @return string
     */
    private function formatSolutionFound($results)
    {
        $solvedResults = array_filter($results, function ($result) {
            return $result->hasSolution();
        });

        $allResultsAreSolved = count($solvedResults) === count($results);
        if ($allResultsAreSolved) {
            return 'Yes';
        }

        $allResultsAreUnsolved = count($solvedResults) === 0;
        if ($allResultsAreUnsolved) {
            return 'No';
        }

        return 'Sometimes';
    }
}
