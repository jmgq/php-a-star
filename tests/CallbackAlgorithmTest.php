<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\CallbackAlgorithm;
use JMGQ\AStar\Node;
use PHPUnit\Framework\TestCase;

class CallbackAlgorithmTest extends TestCase
{
    public function testGenerateAdjacentNodesFunctionIsSet(): void
    {
        $sut = new CallbackAlgorithm(
            $this,
            'adjacentNodesFunction',
            null,
            null
        );

        $node = $this->createStub(Node::class);

        $this->assertSame($this->adjacentNodesFunction(), $sut->generateAdjacentNodes($node));
    }

    public function testRealCostFunctionIsSet(): void
    {
        $sut = new CallbackAlgorithm(
            $this,
            null,
            'realCostFunction',
            null
        );

        $node = $this->createStub(Node::class);

        $this->assertSame($this->realCostFunction(), $sut->calculateRealCost($node, $node));
    }

    public function testEstimatedCostFunctionIsSet(): void
    {
        $sut = new CallbackAlgorithm(
            $this,
            null,
            null,
            'estimatedCostFunction'
        );

        $node = $this->createStub(Node::class);

        $this->assertSame($this->estimatedCostFunction(), $sut->calculateEstimatedCost($node, $node));
    }

    public function adjacentNodesFunction(): string
    {
        return 'foo bar';
    }

    public function realCostFunction(): string
    {
        return 'foo';
    }

    public function estimatedCostFunction(): string
    {
        return 'bar';
    }
}
