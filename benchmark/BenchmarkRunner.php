<?php

namespace JMGQ\AStar\Benchmark;

use JMGQ\AStar\Benchmark\Result\Result;
use JMGQ\AStar\Example\Terrain\MyAStar;
use JMGQ\AStar\Example\Terrain\MyNode;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Stopwatch\Stopwatch;

class BenchmarkRunner
{
    private $progressBar;
    private $terrainGenerator;

    public function __construct(ProgressBar $progressBar)
    {
        $this->progressBar = $progressBar;
        $this->terrainGenerator = new TerrainGenerator();
    }

    /**
     * @param int[] $sizes
     * @param int $iterations
     * @param int | null $seed
     * @return Result[]
     */
    public function run(array $sizes, $iterations, $seed)
    {
        $results = array();

        $steps = count($sizes) * $iterations;
        $this->progressBar->start($steps);

        foreach ($sizes as $size) {
            for ($i = 0; $i < $iterations; $i++) {
                $terrain = $this->terrainGenerator->generate($size, $size, $seed);
                $aStar = new MyAStar($terrain);

                $start = new MyNode(0, 0);
                $goal = new MyNode($size - 1, $size - 1);

                $stopwatch = new Stopwatch();
                $stopwatch->start('benchmark');

                $solution = $aStar->run($start, $goal);

                $event = $stopwatch->stop('benchmark');

                $solutionFound = !empty($solution);

                $results[] = new Result($size, $event->getDuration(), $solutionFound);

                $this->progressBar->advance();
            }
        }

        $this->progressBar->finish();

        return $results;
    }
}
