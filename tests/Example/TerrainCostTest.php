<?php

namespace JMGQ\AStar\Tests\Example;

use JMGQ\AStar\Example\TerrainCost;

class TerrainCostTest extends \PHPUnit_Framework_TestCase
{
    public function validTerrainInformationProvider()
    {
        return array(
            '3x3 terrain' => array(
                array(
                    array(1, 5, 0),
                    array(2, 2, 2),
                    array(2, 8, 9)
                )
            ),
            '4x2 terrain' => array(
                array(
                    array(1, 2),
                    array(0, 0),
                    array(3, PHP_INT_MAX),
                    array(5, 3)
                )
            ),
            'terrain with numbers of string type' => array(
                array(
                    array('2', '3', '0'),
                    array('1', 2, '3')
                )
            ),
            'associative array' => array(
                array(
                    'first row' => array('first column' => 2, 'second column' => 3),
                    'next row' => array('foo' => 0, 'bar' => 5)
                )
            )
        );
    }

    public function emptyTerrainProvider()
    {
        return array(
            'no rows nor columns' => array(
                array()
            ),
            'no columns' => array(
                array(
                    array()
                )
            )
        );
    }

    public function invalidTerrainCostsProvider()
    {
        return array(
            'costs of type float' => array(
                array(
                    array(2.3, 2),
                    array(1, 0)
                )
            ),
            'invalid cost type' => array(
                array(
                    array(false)
                )
            )
        );
    }

    public function nonRectangularTerrainProvider()
    {
        return array(
            array(
                array(
                    array(0, 0, 0),
                    array(0, 0, 0),
                    array(0, 0)
                )
            ),
            array(
                array(
                    array(0, 0, 0),
                    array(0, 0),
                    array(0, 0, 0)
                )
            ),
            array(
                array(
                    array(0),
                    array(0, 0),
                    array(0)
                )
            )
        );
    }

    /**
     * @dataProvider validTerrainInformationProvider
     */
    public function testShouldSetValidTerrainInformation($terrainInformation)
    {
        $sut = new TerrainCost($terrainInformation);

        $expectedRows = count($terrainInformation);
        $expectedColumns = count(reset($terrainInformation));

        $this->assertSame($expectedRows, $sut->getTotalRows());
        $this->assertSame($expectedColumns, $sut->getTotalColumns());

        $row = 0;
        foreach ($terrainInformation as $rowCosts) {
            $column = 0;
            foreach ($rowCosts as $cost) {
                $expectedCost = (int) $cost;

                $this->assertSame($expectedCost, $sut->getCost($row, $column));

                $column++;
            }
            $row++;
        }
    }

    /**
     * @dataProvider emptyTerrainProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage empty
     */
    public function testShouldNotSetEmptyTerrain($emptyTerrain)
    {
        new TerrainCost($emptyTerrain);
    }

    /**
     * @dataProvider invalidTerrainCostsProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid terrain cost
     */
    public function testShouldOnlySetIntegerCosts($invalidTerrain)
    {
        new TerrainCost($invalidTerrain);
    }

    /**
     * @dataProvider nonRectangularTerrainProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage rectangular
     */
    public function testShouldOnlySetRectangularTerrains($nonRectangularTerrain)
    {
        new TerrainCost($nonRectangularTerrain);
    }
}
