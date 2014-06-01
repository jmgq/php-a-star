<?php

namespace JMGQ\AStar\Tests\Example\Connections;

use JMGQ\AStar\Example\Connections\Connection;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    public function validDistanceProvider()
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

    public function invalidDistanceProvider()
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
    public function testShouldSetValidDistance($distance)
    {
        $expectedDistance = (float) $distance;

        $source = $this->getMockBuilder('JMGQ\AStar\Example\Connections\MyNode')
            ->disableOriginalConstructor()
            ->getMock();
        $destination = $this->getMockBuilder('JMGQ\AStar\Example\Connections\MyNode')
            ->disableOriginalConstructor()
            ->getMock();

        $sut = new Connection($source, $destination, $distance);

        $this->assertSame($source, $sut->getSource());
        $this->assertSame($destination, $sut->getDestination());
        $this->assertSame($expectedDistance, $sut->getDistance());
    }

    /**
     * @dataProvider invalidDistanceProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid distance
     */
    public function testShouldNotSetInvalidDistance($distance)
    {
        $source = $this->getMockBuilder('JMGQ\AStar\Example\Connections\MyNode')
            ->disableOriginalConstructor()
            ->getMock();
        $destination = $this->getMockBuilder('JMGQ\AStar\Example\Connections\MyNode')
            ->disableOriginalConstructor()
            ->getMock();

        new Connection($source, $destination, $distance);
    }
}
