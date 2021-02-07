<?php

namespace JMGQ\AStar\Benchmark;

interface ProgressBarInterface
{
    public function start(int $numberOfSteps): void;

    public function advance(): void;

    public function finish(): void;
}
