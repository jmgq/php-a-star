<?php

namespace JMGQ\AStar\Benchmark;

use JMGQ\AStar\Example\Terrain\TerrainCost;

class TerrainGenerator
{
    public function generate(int $rows, int $columns, ?int $seed = null): TerrainCost
    {
        $this->validatePositiveInteger($rows);
        $this->validatePositiveInteger($columns);

        $seed !== null ? mt_srand($seed) : mt_srand();

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
