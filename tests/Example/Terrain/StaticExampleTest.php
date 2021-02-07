<?php

namespace JMGQ\AStar\Tests\Example\Terrain;

use JMGQ\AStar\Example\Terrain\Position;
use JMGQ\AStar\Example\Terrain\StaticExample;
use JMGQ\AStar\Example\Terrain\TerrainCost;
use PHPUnit\Framework\TestCase;

class StaticExampleTest extends TestCase
{
    public function testShouldPrintSolution(): void
    {
        $terrainCost = new TerrainCost([
            [3, 2, 3, 6, 1],
            [1, 3, 4, 1, 1],
            [3, 1, 1, 4, 1],
            [1, 1, 5, 2, 1],
        ]);

        $start = new Position(0, 0);
        $goal = new Position(0, 4);

        $expectedOutput = <<<HEREDOC
  1  -  -  -  6
  2  -  -  5  -
  -  3  4  -  -
  -  -  -  -  -
HEREDOC;

        StaticExample::printSolution($terrainCost, $start, $goal);

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSolution2(): void
    {
        $terrainCost = new TerrainCost([
            [1, 4, 1],
            [1, 5, 1],
            [1, 6, 1],
            [1, 7, 1],
        ]);

        $start = new Position(0, 0);
        $goal = new Position(0, 2);

        $expectedOutput = <<<HEREDOC
  1  2  3
  -  -  -
  -  -  -
  -  -  -
HEREDOC;

        StaticExample::printSolution($terrainCost, $start, $goal);

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSolution3(): void
    {
        $terrainCost = new TerrainCost([
            [3, 2, 3, 6, 1],
            [1, 3, 4, 2, 1],
            [3, 2, 3, 4, 3],
            [1, 1, 5, 3, 1],
        ]);

        $start = new Position(0, 0);
        $goal = new Position(3, 4);

        $expectedOutput = <<<HEREDOC
  1  -  -  -  -
  2  -  -  -  -
  -  3  4  -  -
  -  -  -  5  6
HEREDOC;

        StaticExample::printSolution($terrainCost, $start, $goal);

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSolution4(): void
    {
        $terrainCost = new TerrainCost([
            [1, 1, 9, 1, 1],
            [1, 1, 9, 1, 1],
            [1, 1, 9, 1, 1],
            [1, 1, 1, 1, 1],
        ]);

        $start = new Position(1, 1);
        $goal = new Position(1, 3);

        $expectedOutput = <<<HEREDOC
  -  -  -  -  -
  -  1  -  5  -
  -  2  -  4  -
  -  -  3  -  -
HEREDOC;

        StaticExample::printSolution($terrainCost, $start, $goal);

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSolution5(): void
    {
        $terrainCost = new TerrainCost([
            [1, 4, 4, 5, 1],
            [1, 2, 3, 5, 1],
            [1, 4, 4, 5, 1],
            [1, 1, 1, 1, 1],
        ]);

        $start = new Position(0, 0);
        $goal = new Position(0, 4);

        $expectedOutput = <<<HEREDOC
  1  -  -  -  9
  2  -  -  -  8
  3  -  -  -  7
  -  4  5  6  -
HEREDOC;

        StaticExample::printSolution($terrainCost, $start, $goal);

        $this->expectOutputString($expectedOutput);
    }
}
