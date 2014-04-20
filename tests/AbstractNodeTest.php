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
}
