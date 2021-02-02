<?php

namespace JMGQ\AStar\Benchmark\Result;

use Symfony\Component\Console\Style\StyleInterface;

class ResultPrinter
{
    private $output;

    public function __construct(StyleInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param AggregatedResult[] $results
     */
    public function display(array $results)
    {
        $tableRows = array();

        $orderedResults = $this->orderResults($results);

        foreach ($orderedResults as $result) {
            $size = $result->getSize() . 'x' . $result->getSize();
            $averageDuration = $result->getAverageDuration() . 'ms';
            $minimumDuration = $result->getMinimumDuration() . 'ms';
            $maximumDuration = $result->getMaximumDuration() . 'ms';
            $solutionFound = $this->formatSolutionFound($result);

            $tableRows[] = array($size, $averageDuration, $minimumDuration, $maximumDuration, $solutionFound);
        }

        $tableHeaders = array('Size', 'Avg Duration', 'Min Duration', 'Max Duration', 'Solved?');

        $this->output->table($tableHeaders, $tableRows);
    }

    /**
     * @param AggregatedResult[] $results
     * @return AggregatedResult[]
     */
    private function orderResults(array $results)
    {
        usort($results, function ($a, $b) {
            return $a->getSize() > $b->getSize();
        });

        return $results;
    }

    /**
     * @param AggregatedResult $result
     * @return string
     */
    private function formatSolutionFound($result)
    {
        $allResultsAreSolved = $result->getNumberOfSolutions() === $result->getNumberOfTerrains();
        if ($allResultsAreSolved) {
            return 'Yes';
        }

        $allResultsAreUnsolved = $result->getNumberOfSolutions() === 0;
        if ($allResultsAreUnsolved) {
            return 'No';
        }

        return 'Sometimes';
    }
}
