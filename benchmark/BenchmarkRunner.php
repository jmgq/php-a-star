<?php

namespace JMGQ\AStar\Benchmark;

use JMGQ\AStar\AStar;
use JMGQ\AStar\Benchmark\Result\Result;
use JMGQ\AStar\Example\Terrain\DomainLogic;
use JMGQ\AStar\Example\Terrain\Position;
use Symfony\Component\Stopwatch\Stopwatch;

class BenchmarkRunner
{
    private ProgressBarInterface $progressBar;
    private TerrainGenerator $terrainGenerator;
    private Stopwatch $stopwatch;

    public function __construct(ProgressBarInterface $progressBar)
    {
        $this->progressBar = $progressBar;
        $this->terrainGenerator = new TerrainGenerator();
        $this->stopwatch = new Stopwatch();
    }

    /**
     * @param int[] $sizes
     * @param int $iterations
     * @param int | null $seed
     * @return Result[]
     */
    public function run(array $sizes, int $iterations, ?int $seed): array
    {
        $results = [];

        $steps = count($sizes) * $iterations;
        $this->progressBar->start($steps);

        foreach ($sizes as $size) {
            for ($i = 0; $i < $iterations; $i++) {
                $terrain = $this->terrainGenerator->generate($size, $size, $seed);
                $domainLogic = new DomainLogic($terrain);
                $aStar = new AStar($domainLogic);

                $start = new Position(0, 0);
                $goal = new Position($size - 1, $size - 1);

                $this->stopwatch->start('benchmark');

                $solution = $aStar->run($start, $goal);

                $event = $this->stopwatch->stop('benchmark');

                $solutionFound = !empty($solution);

                $results[] = new Result($size, (int) $event->getDuration(), $solutionFound);

                $this->stopwatch->reset();

                $this->progressBar->advance();
            }
        }

        $this->progressBar->finish();

        return $results;
    }
}
