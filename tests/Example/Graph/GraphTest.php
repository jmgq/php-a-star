<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\Link;
use JMGQ\AStar\Example\Graph\Graph;
use JMGQ\AStar\Example\Graph\MyNode;

class GraphTest extends \PHPUnit_Framework_TestCase
{
    /** @var Graph */
    private $sut;

    public function setUp()
    {
        $this->sut = new Graph();
    }

    public function testShouldAddLink()
    {
        $source = new MyNode(0, 0);
        $destination = new MyNode(1, 1);
        $distance = 123.45;
        $link = new Link($source, $destination, $distance);

        $this->assertFalse($this->sut->hasLink($source, $destination));
        $this->assertNull($this->sut->getLink($source, $destination));

        $this->sut->addLink($link);

        $this->assertTrue($this->sut->hasLink($source, $destination));
        $this->assertSame($link, $this->sut->getLink($source, $destination));
    }

    public function testShouldOverwriteLinksWithSameSourceAndDestination()
    {
        $source = new MyNode(0, 0);
        $destination = new MyNode(1, 1);

        $distance1 = 3;
        $link1 = new Link($source, $destination, $distance1);

        $distance2 = 200;
        $link2 = new Link($source, $destination, $distance2);

        $this->assertFalse($this->sut->hasLink($source, $destination));
        $this->assertNull($this->sut->getLink($source, $destination));

        $this->sut->addLink($link1);

        $this->assertTrue($this->sut->hasLink($source, $destination));
        $this->assertEquals($distance1, $this->sut->getLink($source, $destination)->getDistance());

        $this->sut->addLink($link2);

        $this->assertTrue($this->sut->hasLink($source, $destination));
        $this->assertNotEquals($distance1, $this->sut->getLink($source, $destination)->getDistance());
        $this->assertEquals($distance2, $this->sut->getLink($source, $destination)->getDistance());
    }

    public function testShouldSetLinksInConstructor()
    {
        $source1 = new MyNode(0, 1);
        $destination1 = new MyNode(2, 3);
        $distance1 = 5.5;

        $source2 = new MyNode(4, 5);
        $destination2 = new MyNode(6, 7);
        $distance2 = 27.89;

        $links = array(
            new Link($source1, $destination1, $distance1),
            new Link($source2, $destination2, $distance2)
        );

        $this->sut = new Graph($links);

        $this->assertSame($distance1, $this->sut->getLink($source1, $destination1)->getDistance());
        $this->assertSame($distance2, $this->sut->getLink($source2, $destination2)->getDistance());
    }
}
