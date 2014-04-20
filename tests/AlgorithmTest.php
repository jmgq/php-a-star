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

    public function testOpenListShouldBeInitiallyEmpty()
    {
        $this->assertTrue(is_array($this->sut->getOpenList()));
        $this->assertEmpty($this->sut->getOpenList());
    }

    public function testClosedListShouldBeInitiallyEmpty()
    {
        $this->assertTrue(is_array($this->sut->getClosedList()));
        $this->assertEmpty($this->sut->getClosedList());
    }
}
