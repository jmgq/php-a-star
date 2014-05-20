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

    public function testRealCostFunctionIsSet()
    {
        $sut = new CallbackAlgorithm(
            $this,
            null,
            'realCostFunction',
            null
        );

        $node = $this->getMock('JMGQ\AStar\Node');

        $this->assertSame($this->realCostFunction(), $sut->calculateRealCost($node, $node));
    }

    public function testEstimatedCostFunctionIsSet()
    {
        $sut = new CallbackAlgorithm(
            $this,
            null,
            null,
            'estimatedCostFunction'
        );

        $node = $this->getMock('JMGQ\AStar\Node');

        $this->assertSame($this->estimatedCostFunction(), $sut->calculateEstimatedCost($node, $node));
    }

    public function adjacentNodesFunction()
    {
        return 'foo bar';
    }

    public function realCostFunction()
    {
        return 'foo';
    }

    public function estimatedCostFunction()
    {
        return 'bar';
    }
}
