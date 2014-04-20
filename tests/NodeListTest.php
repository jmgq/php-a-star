<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\NodeList;

class NodeListTest extends \PHPUnit_Framework_TestCase
{
    /** @var NodeList */
    private $sut;

    public function setUp()
    {
        $this->sut = new NodeList();
    }

    public function testShouldBeIterable()
    {
        $this->assertInstanceOf('IteratorAggregate', $this->sut);
    }

    public function testShouldBeInitiallyEmpty()
    {
        $this->assertCount(0, $this->sut);
    }

    public function testShouldAddNodes()
    {
        $node1 = $this->getMock('JMGQ\AStar\Node');
        $node2 = $this->getMock('JMGQ\AStar\Node');

        $this->assertCount(0, $this->sut);

        $this->sut->add($node1);

        $this->assertCount(1, $this->sut);

        $this->sut->add($node2);

        $this->assertCount(2, $this->sut);
        $this->assertContains($node1, $this->sut);
        $this->assertContains($node2, $this->sut);
    }
}
