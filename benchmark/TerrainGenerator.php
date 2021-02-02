<?php

namespace JMGQ\AStar\Benchmark;

use JMGQ\AStar\Example\Terrain\TerrainCost;

class TerrainGenerator
{
    /**
     * @param int $rows
     * @param int $columns
     * @param int | null $seed
     * @return TerrainCost
     */
    public function generate($rows, $columns, $seed = null)
    {
        $this->validatePositiveInteger($rows);
        $this->validatePositiveInteger($columns);
        $this->validateOptionalInteger($seed);

        mt_srand($seed);

        $terrainCost = array();

        foreach (range(0, $rows - 1) as $row) {
            foreach (range(0, $columns - 1) as $column) {
                $terrainCost[$row][$column] = mt_rand(1, 10);
            }
        }

        return new TerrainCost($terrainCost);
    }

    private function validatePositiveInteger($number)
    {
        $positiveInteger = filter_var($number, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

        if ($positiveInteger === false) {
            throw new \InvalidArgumentException('Invalid positive integer: ' . print_r($number, true));
        }
    }

    private function validateOptionalInteger($number)
    {
        $integer = filter_var($number, FILTER_VALIDATE_INT);

        if ($integer === false && $number !== null) {
            throw new \InvalidArgumentException('Invalid integer: ' . print_r($number, true));
        }
    }
}
