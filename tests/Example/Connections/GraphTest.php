<?php

namespace JMGQ\AStar\Tests\Example\Connections;

use JMGQ\AStar\Example\Connections\Connection;
use JMGQ\AStar\Example\Connections\Graph;
use JMGQ\AStar\Example\Connections\MyNode;

class GraphTest extends \PHPUnit_Framework_TestCase
{

    /** @var Graph */
    private $sut;

    public function setUp()
    {
        $this->sut = new Graph();
    }

    public function testShouldAddConnection()
    {
        $source = new MyNode(0, 0);
        $destination = new MyNode(1, 1);
        $distance = 123.45;
        $connection = new Connection($source, $destination, $distance);

        $this->assertFalse($this->sut->hasConnection($source, $destination));
        $this->assertNull($this->sut->getConnection($source, $destination));

        $this->sut->addConnection($connection);

        $this->assertTrue($this->sut->hasConnection($source, $destination));
        $this->assertSame($connection, $this->sut->getConnection($source, $destination));
    }

    public function testShouldOverwriteConnectionsWithSameSourceAndDestination()
    {
        $source = new MyNode(0, 0);
        $destination = new MyNode(1, 1);

        $distance1 = 3;
        $connection1 = new Connection($source, $destination, $distance1);

        $distance2 = 200;
        $connection2 = new Connection($source, $destination, $distance2);

        $this->assertFalse($this->sut->hasConnection($source, $destination));
        $this->assertNull($this->sut->getConnection($source, $destination));

        $this->sut->addConnection($connection1);

        $this->assertTrue($this->sut->hasConnection($source, $destination));
        $this->assertEquals($distance1, $this->sut->getConnection($source, $destination)->getDistance());

        $this->sut->addConnection($connection2);

        $this->assertTrue($this->sut->hasConnection($source, $destination));
        $this->assertNotEquals($distance1, $this->sut->getConnection($source, $destination)->getDistance());
        $this->assertEquals($distance2, $this->sut->getConnection($source, $destination)->getDistance());
    }

    public function testShouldSetConnectionsInConstructor()
    {
        $source1 = new MyNode(0, 1);
        $destination1 = new MyNode(2, 3);
        $distance1 = 5.5;

        $source2 = new MyNode(4, 5);
        $destination2 = new MyNode(6, 7);
        $distance2 = 27.89;

        $connections = array(
            new Connection($source1, $destination1, $distance1),
            new Connection($source2, $destination2, $distance2)
        );

        $this->sut = new Graph($connections);

        $this->assertSame($distance1, $this->sut->getConnection($source1, $destination1)->getDistance());
        $this->assertSame($distance2, $this->sut->getConnection($source2, $destination2)->getDistance());
    }
}
