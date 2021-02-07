<?php

namespace JMGQ\AStar\Benchmark\Result;

use Symfony\Component\Console\Style\StyleInterface;

class ResultPrinter
{
    public function __construct(private StyleInterface $output)
    {
    }

    /**
     * @param AggregatedResult[] $results
     */
    public function display(array $results): void
    {
        $tableRows = [];

        $orderedResults = $this->orderResults($results);

        foreach ($orderedResults as $result) {
            $size = $result->getSize() . 'x' . $result->getSize();
            $averageDuration = $result->getAverageDuration() . 'ms';
            $minimumDuration = $result->getMinimumDuration() . 'ms';
            $maximumDuration = $result->getMaximumDuration() . 'ms';
            $solutionFound = $this->formatSolutionFound($result);

            $tableRows[] = [$size, $averageDuration, $minimumDuration, $maximumDuration, $solutionFound];
        }

        $tableHeaders = ['Size', 'Avg Duration', 'Min Duration', 'Max Duration', 'Solved?'];

        $this->output->table($tableHeaders, $tableRows);
    }

    /**
     * @param AggregatedResult[] $results
     * @return AggregatedResult[]
     */
    private function orderResults(array $results): array
    {
        usort($results, static fn (AggregatedResult $a, AggregatedResult $b) => $a->getSize() - $b->getSize());

        return $results;
    }

    private function formatSolutionFound(AggregatedResult $result): string
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
