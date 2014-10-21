<?php

namespace JMGQ\AStar\Tests\Example\Terrain;

use JMGQ\AStar\Example\Terrain\MyNode;
use JMGQ\AStar\Example\Terrain\StaticExample;
use JMGQ\AStar\Example\Terrain\TerrainCost;

class StaticExampleTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldPrintSolution()
    {
        $terrainCost = new TerrainCost(
            array(
                array(3, 2, 3, 6, 1),
                array(1, 3, 4, 1, 1),
                array(3, 1, 1, 4, 1),
                array(1, 1, 5, 2, 1)
            )
        );

        $start = new MyNode(0, 0);
        $goal = new MyNode(0, 4);

        $expectedOutput = <<<HEREDOC
  1  -  -  -  6
  2  -  -  5  -
  -  3  4  -  -
  -  -  -  -  -
HEREDOC;

        StaticExample::printSolution($terrainCost, $start, $goal);

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSolution2()
    {
        $terrainCost = new TerrainCost(
            array(
                array(1, 4, 1),
                array(1, 5, 1),
                array(1, 6, 1),
                array(1, 7, 1)
            )
        );

        $start = new MyNode(0, 0);
        $goal = new MyNode(0, 2);

        $expectedOutput = <<<HEREDOC
  1  2  3
  -  -  -
  -  -  -
  -  -  -
HEREDOC;

        StaticExample::printSolution($terrainCost, $start, $goal);

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSolution3()
    {
        $terrainCost = new TerrainCost(
            array(
                array(3, 2, 3, 6, 1),
                array(1, 3, 4, 1, 1),
                array(3, 2, 3, 4, 3),
                array(1, 1, 5, 3, 1)
            )
        );

        $start = new MyNode(0, 0);
        $goal = new MyNode(3, 4);

        $expectedOutput = <<<HEREDOC
  1  2  3  -  -
  -  -  -  4  -
  -  -  -  -  5
  -  -  -  -  6
HEREDOC;

        StaticExample::printSolution($terrainCost, $start, $goal);

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSolution4()
    {
        $terrainCost = new TerrainCost(
            array(
                array(1, 1, 9, 1, 1),
                array(1, 1, 9, 1, 1),
                array(1, 1, 9, 1, 1),
                array(1, 1, 1, 1, 1)
            )
        );

        $start = new MyNode(1, 1);
        $goal = new MyNode(1, 3);

        $expectedOutput = <<<HEREDOC
  -  -  -  -  -
  -  1  -  5  -
  -  2  -  4  -
  -  -  3  -  -
HEREDOC;

        StaticExample::printSolution($terrainCost, $start, $goal);

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSolution5()
    {
        $terrainCost = new TerrainCost(
            array(
                array(1, 4, 4, 5, 1),
                array(1, 2, 3, 5, 1),
                array(1, 4, 4, 5, 1),
                array(1, 1, 1, 1, 1)
            )
        );

        $start = new MyNode(0, 0);
        $goal = new MyNode(0, 4);

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
