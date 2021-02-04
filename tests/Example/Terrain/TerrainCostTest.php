<?php

namespace JMGQ\AStar\Tests\Example\Terrain;

use JMGQ\AStar\Example\Terrain\TerrainCost;
use PHPUnit\Framework\TestCase;

class TerrainCostTest extends TestCase
{
    public function validTerrainInformationProvider(): array
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

    public function emptyTerrainProvider(): array
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

    public function invalidTerrainCostsProvider(): array
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

    public function nonRectangularTerrainProvider(): array
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

    public function invalidPointProvider(): array
    {
        return array(
            array(-1, 3),
            array(4, PHP_INT_MAX),
            array(0, 'foo'),
            array('bar', 0)
        );
    }

    /**
     * @dataProvider validTerrainInformationProvider
     */
    public function testShouldSetValidTerrainInformation(array $terrainInformation): void
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
     */
    public function testShouldNotSetEmptyTerrain(array $emptyTerrain): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('empty');

        new TerrainCost($emptyTerrain);
    }

    /**
     * @dataProvider invalidTerrainCostsProvider
     */
    public function testShouldOnlySetIntegerCosts(array $invalidTerrain): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid terrain cost');

        new TerrainCost($invalidTerrain);
    }

    /**
     * @dataProvider nonRectangularTerrainProvider
     */
    public function testShouldOnlySetRectangularTerrains(array $nonRectangularTerrain): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('rectangular');

        new TerrainCost($nonRectangularTerrain);
    }

    /**
     * @dataProvider invalidPointProvider
     */
    public function testShouldThrowExceptionIfTheRequestedTileDoesNotExist($row, $column): void
    {
        $sut = new TerrainCost(
            array(
                array(0, 0, 0),
                array(0, 0, 0),
                array(0, 0, 0)
            )
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid tile');

        $sut->getCost($row, $column);
    }
}
