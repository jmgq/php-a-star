<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\AbstractNode;

class AbstractNodeTest extends \PHPUnit_Framework_TestCase
{
    /** @var AbstractNode */
    private $sut;

    public function setUp()
    {
        $this->sut = $this->getMockForAbstractClass('JMGQ\AStar\AbstractNode');
    }

    public function testShouldHaveNoParentInitially()
    {
        $this->assertNull($this->sut->getParent());
    }

    public function testShouldSetParent()
    {
        $parent = $this->getMock('JMGQ\AStar\Node');

        $this->assertNull($this->sut->getParent());

        $this->sut->setParent($parent);

        $this->assertSame($parent, $this->sut->getParent());
    }

    public function testShouldHaveNoChildrenInitially()
    {
        $this->assertCount(0, $this->sut->getChildren());
    }

    public function testShouldAddChild()
    {
        $child = $this->getMock('JMGQ\AStar\Node');

        $this->assertCount(0, $this->sut->getChildren());

        $this->sut->addChild($child);

        $this->assertCount(1, $this->sut->getChildren());
    }

    public function testShouldSetItselfAsTheParentOfItsChildren()
    {
        $child = $this->getMockForAbstractClass('JMGQ\AStar\AbstractNode');

        $this->assertNull($child->getParent());

        $this->sut->addChild($child);

        $this->assertSame($this->sut, $child->getParent());
    }

    public function testShouldSetG()
    {
        $score = 5;

        $this->sut->setG($score);

        $this->assertSame($score, $this->sut->getG());
    }

    public function testShouldSetH()
    {
        $score = 4;

        $this->sut->setH($score);

        $this->assertSame($score, $this->sut->getH());
    }

    public function testShouldGetF()
    {
        $g = 3;
        $h = 5;
        $expectedF = $g + $h;

        $this->sut->setG($g);
        $this->sut->setH($h);

        $this->assertSame($expectedF, $this->sut->getF());
    }
}
