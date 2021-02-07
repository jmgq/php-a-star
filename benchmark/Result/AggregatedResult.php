<?php

namespace JMGQ\AStar\Benchmark\Result;

class AggregatedResult
{
    private int $size;
    private int $averageDuration;
    private int $minimumDuration;
    private int $maximumDuration;
    private int $numberOfSolutions;
    private int $numberOfTerrains;

    public function __construct(
        int $size,
        int $averageDuration,
        int $minimumDuration,
        int $maximumDuration,
        int $numberOfSolutions,
        int $numberOfTerrains,
    ) {
        $this->size = $this->filterNaturalNumber($size, 'size');
        $this->averageDuration = $this->filterNonNegativeInteger($averageDuration, 'average duration');
        $this->minimumDuration = $this->filterNonNegativeInteger($minimumDuration, 'minimum duration');
        $this->maximumDuration = $this->filterNonNegativeInteger($maximumDuration, 'maximum duration');
        $this->numberOfSolutions = $this->filterNonNegativeInteger($numberOfSolutions, 'number of solutions');
        $this->numberOfTerrains = $this->filterNaturalNumber($numberOfTerrains, 'number of terrains');
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getAverageDuration(): int
    {
        return $this->averageDuration;
    }

    public function getMinimumDuration(): int
    {
        return $this->minimumDuration;
    }

    public function getMaximumDuration(): int
    {
        return $this->maximumDuration;
    }

    public function getNumberOfSolutions(): int
    {
        return $this->numberOfSolutions;
    }

    public function getNumberOfTerrains(): int
    {
        return $this->numberOfTerrains;
    }

    private function filterNaturalNumber(int $value, string $parameterName): int
    {
        if ($value < 1) {
            throw new \InvalidArgumentException("Invalid $parameterName: $value");
        }

        return $value;
    }

    private function filterNonNegativeInteger(int $value, string $parameterName): int
    {
        if ($value < 0) {
            throw new \InvalidArgumentException("Invalid $parameterName: $value");
        }

        return $value;
    }
}
