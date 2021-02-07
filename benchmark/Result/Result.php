<?php

namespace JMGQ\AStar\Benchmark\Result;

class Result
{
    private int $size;
    private int $duration;
    private bool $hasSolution;

    public function __construct(int $size, int $duration, bool $hasSolution)
    {
        $this->size = $this->filterSize($size);
        $this->duration = $this->filterDuration($duration);
        $this->hasSolution = $hasSolution;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function hasSolution(): bool
    {
        return $this->hasSolution;
    }

    private function filterSize(int $size): int
    {
        if ($size < 1) {
            throw new \InvalidArgumentException("Invalid size: $size");
        }

        return $size;
    }

    private function filterDuration(int $duration): int
    {
        if ($duration < 0) {
            throw new \InvalidArgumentException("Invalid duration: $duration");
        }

        return $duration;
    }
}
