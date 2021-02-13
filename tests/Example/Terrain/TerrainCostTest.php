<?php

namespace JMGQ\AStar\Tests\Example\Terrain;

use JMGQ\AStar\Example\Terrain\TerrainCost;
use PHPUnit\Framework\TestCase;

class TerrainCostTest extends TestCase
{
    /**
     * @return mixed[][][][]
     */
    public function validTerrainInformationProvider(): array
    {
        return [
            '3x3 terrain' => [
                [
                    [1, 5, 0],
                    [2, 2, 2],
                    [2, 8, 9]
                ]
            ],
            '4x2 terrain' => [
                [
                    [1, 2],
                    [0, 0],
                    [3, PHP_INT_MAX],
                    [5, 3]
                ]
            ],
            'terrain with numbers of string type' => [
                [
                    ['2', '3', '0'],
                    ['1', 2, '3']
                ]
            ],
            'associative array' => [
                [
                    'first row' => ['first column' => 2, 'second column' => 3],
                    'next row' => ['foo' => 0, 'bar' => 5]
                ]
            ]
        ];
    }

    /**
     * @return mixed[][][]
     */
    public function emptyTerrainProvider(): array
    {
        return [
            'no rows nor columns' => [
                []
            ],
            'no columns' => [
                [
                    []
                ]
            ]
        ];
    }

    /**
     * @return mixed[][][][]
     */
    public function invalidTerrainCostsProvider(): array
    {
        return [
            'costs of type float' => [
                [
                    [2.3, 2],
                    [1, 0]
                ]
            ],
            'invalid cost type' => [
                [
                    [false]
                ]
            ]
        ];
    }

    /**
     * @return int[][][][]
     */
    public function nonRectangularTerrainProvider(): array
    {
        return [
            [
                [
                    [0, 0, 0],
                    [0, 0, 0],
                    [0, 0]
                ]
            ],
            [
                [
                    [0, 0, 0],
                    [0, 0],
                    [0, 0, 0]
                ]
            ],
            [
                [
                    [0],
                    [0, 0],
                    [0]
                ]
            ]
        ];
    }

    /**
     * @return mixed[][]
     */
    public function invalidPointProvider(): array
    {
        return [
            [-1, 3, \InvalidArgumentException::class, 'Invalid tile'],
            [4, PHP_INT_MAX, \InvalidArgumentException::class, 'Invalid tile'],
            [0, 'foo', \TypeError::class, 'must be of type int'],
            ['bar', 0, \TypeError::class, 'must be of type int'],
        ];
    }

    /**
     * @dataProvider validTerrainInformationProvider
     * @param mixed[][] $terrainInformation
     */
    public function testShouldSetValidTerrainInformation(array $terrainInformation): void
    {
        $sut = new TerrainCost($terrainInformation);

        $expectedRows = count($terrainInformation);

        // @phpstan-ignore-next-line reset won't return false as the terrain information will be valid
        $expectedColumns = count(reset($terrainInformation));

        $this->assertSame($expectedRows, $sut->getTotalRows());
        $this->assertSame($expectedColumns, $sut->getTotalColumns());

        $row = 0;
        foreach ($terrainInformation as $rowCosts) {
            $column = 0;
            /** @var numeric $cost */
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
     * @param mixed[] $emptyTerrain
     */
    public function testShouldNotSetEmptyTerrain(array $emptyTerrain): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('empty');

        new TerrainCost($emptyTerrain);
    }

    /**
     * @dataProvider invalidTerrainCostsProvider
     * @param mixed[][] $invalidTerrain
     */
    public function testShouldOnlySetIntegerCosts(array $invalidTerrain): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid terrain cost');

        new TerrainCost($invalidTerrain);
    }

    /**
     * @dataProvider nonRectangularTerrainProvider
     * @param int[][] $nonRectangularTerrain
     */
    public function testShouldOnlySetRectangularTerrains(array $nonRectangularTerrain): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('rectangular');

        new TerrainCost($nonRectangularTerrain);
    }

    /**
     * @dataProvider invalidPointProvider
     * @param mixed $row
     * @param mixed $column
     * @param class-string<\Throwable> $expectedException
     * @param string $expectedExceptionMessage
     */
    public function testShouldThrowExceptionIfTheRequestedTileDoesNotExist(
        mixed $row,
        mixed $column,
        string $expectedException,
        string $expectedExceptionMessage,
    ): void {
        $sut = new TerrainCost([
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0]
        ]);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $sut->getCost($row, $column);
    }
}
