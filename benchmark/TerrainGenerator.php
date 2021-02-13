<?php

namespace JMGQ\AStar\Benchmark;

use JMGQ\AStar\Example\Terrain\TerrainCost;

class TerrainGenerator
{
    public function generate(int $rows, int $columns, ?int $seed = null): TerrainCost
    {
        $this->validatePositiveInteger($rows);
        $this->validatePositiveInteger($columns);

        // @phpstan-ignore-next-line mt_srand accepts null or int as its seed
        mt_srand($seed);

        $terrainCost = [];

        foreach (range(0, $rows - 1) as $row) {
            foreach (range(0, $columns - 1) as $column) {
                $terrainCost[$row][$column] = mt_rand(1, 10);
            }
        }

        return new TerrainCost($terrainCost);
    }

    private function validatePositiveInteger(int $number): void
    {
        if ($number < 1) {
            throw new \InvalidArgumentException("Invalid positive integer: $number");
        }
    }
}
