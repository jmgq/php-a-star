<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\AbstractNode;
use JMGQ\AStar\Node;
use PHPUnit\Framework\TestCase;

class AbstractNodeTest extends TestCase
{
    private AbstractNode $sut;

    public function validNumberProvider(): array
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

    public function invalidNumberProvider(): array
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

    protected function setUp(): void
    {
        $this->sut = $this->getMockForAbstractClass(AbstractNode::class);
    }

    public function testShouldHaveNoParentInitially(): void
    {
        $this->assertNull($this->sut->getParent());
    }

    public function testShouldSetParent(): void
    {
        $parent = $this->createStub(Node::class);

        $this->assertNull($this->sut->getParent());

        $this->sut->setParent($parent);

        $this->assertSame($parent, $this->sut->getParent());
    }

    public function testShouldHaveNoChildrenInitially(): void
    {
        $this->assertCount(0, $this->sut->getChildren());
    }

    public function testShouldAddChild(): void
    {
        $child = $this->createStub(Node::class);

        $this->assertCount(0, $this->sut->getChildren());

        $this->sut->addChild($child);

        $this->assertCount(1, $this->sut->getChildren());
    }

    public function testShouldSetItselfAsTheParentOfItsChildren(): void
    {
        $child = $this->getMockForAbstractClass(AbstractNode::class);

        $this->assertNull($child->getParent());

        $this->sut->addChild($child);

        $this->assertSame($this->sut, $child->getParent());
    }

    /**
     * @dataProvider validNumberProvider
     */
    public function testShouldSetValidG($validScore): void
    {
        $this->sut->setG($validScore);

        $this->assertSame($validScore, $this->sut->getG());
    }

    /**
     * @dataProvider invalidNumberProvider
     */
    public function testShouldNotSetInvalidG($invalidScore): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->sut->setG($invalidScore);
    }

    /**
     * @dataProvider validNumberProvider
     */
    public function testShouldSetValidH($validScore): void
    {
        $this->sut->setH($validScore);

        $this->assertSame($validScore, $this->sut->getH());
    }

    /**
     * @dataProvider invalidNumberProvider
     */
    public function testShouldNotSetInvalidH($invalidScore): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->sut->setH($invalidScore);
    }

    public function testShouldGetF(): void
    {
        $g = 3;
        $h = 5;
        $expectedF = $g + $h;

        $this->sut->setG($g);
        $this->sut->setH($h);

        $this->assertSame($expectedF, $this->sut->getF());
    }
}
