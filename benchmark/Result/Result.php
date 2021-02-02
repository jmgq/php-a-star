<?php

namespace JMGQ\AStar\Benchmark\Result;

class Result
{
    private $size;
    private $duration;
    private $hasSolution;

    /**
     * @param int $size
     * @param int $duration
     * @param bool $hasSolution
     */
    public function __construct($size, $duration, $hasSolution)
    {
        $this->size = $this->filterSize($size);
        $this->duration = $this->filterDuration($duration);
        $this->hasSolution = $this->filterHasSolution($hasSolution);
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function hasSolution()
    {
        return $this->hasSolution;
    }

    private function filterSize($size)
    {
        $naturalNumber = filter_var($size, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

        if ($naturalNumber === false) {
            throw new \InvalidArgumentException('Invalid size: ' . print_r($size, true));
        }

        return $naturalNumber;
    }

    private function filterDuration($duration)
    {
        $nonNegativeInteger = filter_var($duration, FILTER_VALIDATE_INT, array('options' => array('min_range' => 0)));

        if ($nonNegativeInteger === false) {
            throw new \InvalidArgumentException('Invalid duration: ' . print_r($duration, true));
        }

        return $nonNegativeInteger;
    }

    private function filterHasSolution($hasSolution)
    {
        return filter_var($hasSolution, FILTER_VALIDATE_BOOLEAN);
    }
}
