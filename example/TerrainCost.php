<?php

namespace JMGQ\AStar\Example;

class TerrainCost
{
    private $terrainCost;

    public function __construct(array $terrainCost)
    {
        if (TerrainCost::isEmpty($terrainCost)) {
            throw new \InvalidArgumentException('The terrain costs array is empty');
        }

        if (!TerrainCost::isRectangular($terrainCost)) {
            throw new \InvalidArgumentException('The terrain costs array is not rectangular');
        }

        $terrainCost = TerrainCost::convertToNumericArray($terrainCost);

        $this->terrainCost = TerrainCost::validateTerrainCosts($terrainCost);
    }

    public function getCost($row, $column)
    {
        return $this->terrainCost[$row][$column];
    }

    public function getTotalRows()
    {
        return count($this->terrainCost);
    }

    public function getTotalColumns()
    {
        return count($this->terrainCost[0]);
    }

    private static function isEmpty(array $terrainCost)
    {
        if (!empty($terrainCost)) {
            $firstRow = reset($terrainCost);

            return empty($firstRow);
        }

        return true;
    }

    private static function validateTerrainCosts(array $terrain)
    {
        $validTerrain = array();

        foreach ($terrain as $row => $rowValues) {
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

    private static function convertToNumericArray(array $associativeArray)
    {
        $numericArray = array();

        foreach ($associativeArray as $row) {
            $numericArray[] = array_values($row);
        }

        return $numericArray;
    }

    private static function isRectangular(array $terrain)
    {
        $numberOfColumnsInFirstRow = count(reset($terrain));

        foreach ($terrain as $row) {
            if (count($row) != $numberOfColumnsInFirstRow) {
                return false;
            }
        }

        return true;
    }
}
