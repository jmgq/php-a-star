<?php

namespace JMGQ\AStar\Benchmark\Result;

class AggregatedResult
{
    private $size;
    private $averageDuration;
    private $minimumDuration;
    private $maximumDuration;
    private $numberOfSolutions;
    private $numberOfTerrains;

    /**
     * @param int $size
     * @param int $averageDuration
     * @param int $minimumDuration
     * @param int $maximumDuration
     * @param int $numberOfSolutions
     * @param int $numberOfTerrains
     */
    public function __construct(
        $size,
        $averageDuration,
        $minimumDuration,
        $maximumDuration,
        $numberOfSolutions,
        $numberOfTerrains
    ) {
        $this->size = $this->filterNaturalNumber($size, 'size');
        $this->averageDuration = $this->filterNonNegativeInteger($averageDuration, 'average duration');
        $this->minimumDuration = $this->filterNonNegativeInteger($minimumDuration, 'minimum duration');
        $this->maximumDuration = $this->filterNonNegativeInteger($maximumDuration, 'maximum duration');
        $this->numberOfSolutions = $this->filterNonNegativeInteger($numberOfSolutions, 'number of solutions');
        $this->numberOfTerrains = $this->filterNaturalNumber($numberOfTerrains, 'number of terrains');
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getAverageDuration()
    {
        return $this->averageDuration;
    }

    public function getMinimumDuration()
    {
        return $this->minimumDuration;
    }

    public function getMaximumDuration()
    {
        return $this->maximumDuration;
    }

    public function getNumberOfSolutions()
    {
        return $this->numberOfSolutions;
    }

    public function getNumberOfTerrains()
    {
        return $this->numberOfTerrains;
    }

    private function filterNaturalNumber($value, $parameterName)
    {
        $naturalNumber = filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

        if ($naturalNumber === false) {
            throw new \InvalidArgumentException('Invalid ' . $parameterName . ': ' . print_r($value, true));
        }

        return $naturalNumber;
    }

    private function filterNonNegativeInteger($value, $parameterName)
    {
        $nonNegativeInteger = filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 0)));

        if ($nonNegativeInteger === false) {
            throw new \InvalidArgumentException('Invalid ' . $parameterName . ': ' . print_r($value, true));
        }

        return $nonNegativeInteger;
    }
}
