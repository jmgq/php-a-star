<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\CallbackAlgorithm;

class CallbackAlgorithmTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateAdjacentNodesFunctionIsSet()
    {
        $sut = new CallbackAlgorithm(
            $this,
            'adjacentNodesFunction',
            null,
            null
        );

        $node = $this->getMock('JMGQ\AStar\Node');

        $this->assertSame($this->adjacentNodesFunction(), $sut->generateAdjacentNodes($node));
    }

    public function testRealDistanceFunctionIsSet()
    {
        $sut = new CallbackAlgorithm(
            $this,
            null,
            'realDistanceFunction',
            null
        );

        $node = $this->getMock('JMGQ\AStar\Node');

        $this->assertSame($this->realDistanceFunction(), $sut->calculateRealDistance($node, $node));
    }

    public function testHeuristicDistanceFunctionIsSet()
    {
        $sut = new CallbackAlgorithm(
            $this,
            null,
            null,
            'heuristicDistanceFunction'
        );

        $node = $this->getMock('JMGQ\AStar\Node');

        $this->assertSame($this->heuristicDistanceFunction(), $sut->calculateHeuristicDistance($node, $node));
    }

    public function adjacentNodesFunction()
    {
        return 'foo bar';
    }

    public function realDistanceFunction()
    {
        return 'foo';
    }

    public function heuristicDistanceFunction()
    {
        return 'bar';
    }
}
