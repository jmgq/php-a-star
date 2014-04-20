<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\Algorithm;

class AlgorithmTest extends \PHPUnit_Framework_TestCase
{
    /** @var Algorithm */
    private $sut;

    public function setUp()
    {
        $this->sut = new Algorithm();
    }

    public function testOpenListShouldBeANodeList()
    {
        $this->assertInstanceOf('JMGQ\AStar\NodeList', $this->sut->getOpenList());
    }

    public function testClosedListShouldBeANodeList()
    {
        $this->assertInstanceOf('JMGQ\AStar\NodeList', $this->sut->getClosedList());
    }
}
