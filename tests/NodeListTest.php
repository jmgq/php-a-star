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
}
