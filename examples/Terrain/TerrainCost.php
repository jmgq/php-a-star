<?php

namespace JMGQ\AStar\Example\Terrain;

class TerrainCost
{
    public const INFINITE = PHP_INT_MAX;

    /** @var int[][] */
    private array $terrainCost;

    /**
     * @param int[][] $terrainCost
     */
    public function __construct(array $terrainCost)
    {
        if (self::isEmpty($terrainCost)) {
            throw new \InvalidArgumentException('The terrain costs array is empty');
        }

        if (!self::isRectangular($terrainCost)) {
            throw new \InvalidArgumentException('The terrain costs array is not rectangular');
        }

        $terrainCost = self::convertToNumericArray($terrainCost);

        $this->terrainCost = self::validateTerrainCosts($terrainCost);
    }

    public function getCost(int $row, int $column): int
    {
        if (!isset($this->terrainCost[$row][$column])) {
            throw new \InvalidArgumentException("Invalid tile: $row, $column");
        }

        return $this->terrainCost[$row][$column];
    }

    public function getTotalRows(): int
    {
        return count($this->terrainCost);
    }

    public function getTotalColumns(): int
    {
        return count($this->terrainCost[0]);
    }

    /**
     * @param int[][] $terrainCost
     * @return bool
     */
    private static function isEmpty(array $terrainCost): bool
    {
        if (!empty($terrainCost)) {
            $firstRow = reset($terrainCost);

            return empty($firstRow);
        }

        return true;
    }

    /**
     * @param mixed[][] $terrain
     * @return int[][]
     */
    private static function validateTerrainCosts(array $terrain): array
    {
        $validTerrain = [];

        foreach ($terrain as $row => $rowValues) {
            /** @psalm-suppress MixedAssignment PSalm is unable to determine that $value is of mixed type */
            foreach ($rowValues as $column => $value) {
                $integerValue = filter_var($value, FILTER_VALIDATE_INT);

                if ($integerValue === false) {
                    throw new \InvalidArgumentException('Invalid terrain cost: ' . print_r($value, true));
                }

                $validTerrain[$row][$column] = $integerValue;
            }
        }

        return $validTerrain;
    }

    /**
     * @param int[][] $associativeArray
     * @return int[][]
     */
    private static function convertToNumericArray(array $associativeArray): array
    {
        $numericArray = [];

        foreach ($associativeArray as $row) {
            $numericArray[] = array_values($row);
        }

        return $numericArray;
    }

    /**
     * @param int[][] $terrain
     * @return bool
     */
    private static function isRectangular(array $terrain): bool
    {
        // @phpstan-ignore-next-line reset won't return false as we have already checked that the terrain is not empty
        $numberOfColumnsInFirstRow = count(reset($terrain));

        foreach ($terrain as $row) {
            if (count($row) !== $numberOfColumnsInFirstRow) {
                return false;
            }
        }

        return true;
    }
}
