<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\Link;
use JMGQ\AStar\Example\Graph\MyNode;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public function validDistanceProvider(): array
    {
        return array(
            array(0),
            array(3),
            array('7'),
            array(PHP_INT_MAX),
            array(7.5),
            array('12.345')
        );
    }

    public function invalidDistanceProvider(): array
    {
        return array(
            array(-1),
            array(-0.5),
            array(null),
            array('a'),
            array(array()),
            array(false)
        );
    }

    /**
     * @dataProvider validDistanceProvider
     */
    public function testShouldSetValidDistance($distance): void
    {
        $expectedDistance = (float) $distance;

        $source = $this->createStub(MyNode::class);
        $destination = $this->createStub(MyNode::class);

        $sut = new Link($source, $destination, $distance);

        $this->assertSame($source, $sut->getSource());
        $this->assertSame($destination, $sut->getDestination());
        $this->assertSame($expectedDistance, $sut->getDistance());
    }

    /**
     * @dataProvider invalidDistanceProvider
     */
    public function testShouldNotSetInvalidDistance($distance): void
    {
        $source = $this->createStub(MyNode::class);
        $destination = $this->createStub(MyNode::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid distance');

        new Link($source, $destination, $distance);
    }
}
