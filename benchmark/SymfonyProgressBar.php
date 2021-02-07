<?php

namespace JMGQ\AStar\Benchmark;

use Symfony\Component\Console\Helper\ProgressBar;

class SymfonyProgressBar implements ProgressBarInterface
{
    public function __construct(private ProgressBar $progressBar)
    {
    }

    public function start(int $numberOfSteps): void
    {
        $this->progressBar->start($numberOfSteps);
    }

    public function advance(): void
    {
        $this->progressBar->advance();
    }

    public function finish(): void
    {
        $this->progressBar->finish();
    }
}
