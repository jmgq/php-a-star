<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\AbstractNode;

class AbstractNodeTest extends \PHPUnit_Framework_TestCase
{
    /** @var AbstractNode */
    private $sut;

    public function validNumberProvider()
    {
        return array(
            array(1),
            array(1.5),
            array('1.5'),
            array('200'),
            array(0),
            array(PHP_INT_MAX)
        );
    }

    public function invalidNumberProvider()
    {
        return array(
            array('a'),
            array(array()),
            array(false),
            array(true),
            array(null),
            array(''),
            array(' ')
        );
    }

    protected function setUp()
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

    /**
     * @dataProvider validNumberProvider
     */
    public function testShouldSetValidG($validScore)
    {
        $this->sut->setG($validScore);

        $this->assertSame($validScore, $this->sut->getG());
    }

    /**
     * @dataProvider invalidNumberProvider
     * @expectedException \InvalidArgumentException
     */
    public function testShouldNotSetInvalidG($invalidScore)
    {
        $this->sut->setG($invalidScore);
    }

    /**
     * @dataProvider validNumberProvider
     */
    public function testShouldSetValidH($validScore)
    {
        $this->sut->setH($validScore);

        $this->assertSame($validScore, $this->sut->getH());
    }

    /**
     * @dataProvider invalidNumberProvider
     * @expectedException \InvalidArgumentException
     */
    public function testShouldNotSetInvalidH($invalidScore)
    {
        $this->sut->setH($invalidScore);
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
