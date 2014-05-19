<?php

namespace JMGQ\AStar\Tests\Example;

use JMGQ\AStar\Example\Example;
use JMGQ\AStar\Example\MyNode;
use JMGQ\AStar\Example\TerrainCost;

class ExampleTest extends \PHPUnit_Framework_TestCase
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

        $sut = new Example($terrainCost);

        $sut->printSolution($start, $goal);

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintSolution2()
    {
        $terrainCost = new TerrainCost(
            array(
                array(1, 4, 1),
                array(1, 4, 1),
                array(1, 4, 1),
                array(1, 4, 1)
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

        $sut = new Example($terrainCost);

        $sut->printSolution($start, $goal);

        $this->expectOutputString($expectedOutput);
    }
}
