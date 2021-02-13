<?php

namespace JMGQ\AStar\Tests\Example\Graph;

use JMGQ\AStar\Example\Graph\Coordinate;
use JMGQ\AStar\Example\Graph\Graph;
use JMGQ\AStar\Example\Graph\Link;
use PHPUnit\Framework\TestCase;

class GraphTest extends TestCase
{
    private Graph $sut;

    protected function setUp(): void
    {
        $this->sut = new Graph();
    }

    public function testShouldAddLink(): void
    {
        $source = new Coordinate(0, 0);
        $destination = new Coordinate(1, 1);
        $distance = 123.45;
        $link = new Link($source, $destination, $distance);

        $this->assertFalse($this->sut->hasLink($source, $destination));
        $this->assertNull($this->sut->getLink($source, $destination));

        $this->sut->addLink($link);

        $this->assertTrue($this->sut->hasLink($source, $destination));
        $this->assertSame($link, $this->sut->getLink($source, $destination));
    }

    public function testShouldOverwriteLinksWithSameSourceAndDestination(): void
    {
        $source = new Coordinate(0, 0);
        $destination = new Coordinate(1, 1);

        $distance1 = 3;
        $link1 = new Link($source, $destination, $distance1);

        $distance2 = 200;
        $link2 = new Link($source, $destination, $distance2);

        $this->assertFalse($this->sut->hasLink($source, $destination));
        $this->assertNull($this->sut->getLink($source, $destination));

        $this->sut->addLink($link1);

        $this->assertTrue($this->sut->hasLink($source, $destination));
        $this->assertEquals($distance1, $this->sut->getLink($source, $destination)?->getDistance());

        $this->sut->addLink($link2);

        $this->assertTrue($this->sut->hasLink($source, $destination));
        $this->assertNotEquals($distance1, $this->sut->getLink($source, $destination)?->getDistance());
        $this->assertEquals($distance2, $this->sut->getLink($source, $destination)?->getDistance());
    }

    public function testShouldSetLinksInConstructor(): void
    {
        $source1 = new Coordinate(0, 1);
        $destination1 = new Coordinate(2, 3);
        $distance1 = 5.5;

        $source2 = new Coordinate(4, 5);
        $destination2 = new Coordinate(6, 7);
        $distance2 = 27.89;

        $links = [
            new Link($source1, $destination1, $distance1),
            new Link($source2, $destination2, $distance2)
        ];

        $this->sut = new Graph($links);

        $this->assertSame($distance1, $this->sut->getLink($source1, $destination1)?->getDistance());
        $this->assertSame($distance2, $this->sut->getLink($source2, $destination2)?->getDistance());
    }

    public function testShouldGetDirectSuccessors(): void
    {
        $nodeA = new Coordinate(0, 0);
        $nodeB = new Coordinate(1, 1);
        $nodeC = new Coordinate(2, 2);
        $nodeD = new Coordinate(3, 3);
        $distance = 1;

        $this->sut->addLink(new Link($nodeA, $nodeB, $distance));
        $this->sut->addLink(new Link($nodeA, $nodeC, $distance));
        $this->sut->addLink(new Link($nodeB, $nodeD, $distance));

        $nodeADirectSuccessors = $this->sut->getDirectSuccessors($nodeA);
        $nodeBDirectSuccessors = $this->sut->getDirectSuccessors($nodeB);
        $nodeCDirectSuccessors = $this->sut->getDirectSuccessors($nodeC);
        $nodeDDirectSuccessors = $this->sut->getDirectSuccessors($nodeD);

        $this->assertCount(2, $nodeADirectSuccessors);
        $this->assertCount(1, $nodeBDirectSuccessors);
        $this->assertCount(0, $nodeCDirectSuccessors);
        $this->assertCount(0, $nodeDDirectSuccessors);

        foreach ($nodeADirectSuccessors as $successor) {
            $this->assertTrue($successor->getId() === $nodeB->getId() || $successor->getId() === $nodeC->getId());
        }

        $this->assertSame($nodeD->getId(), $nodeBDirectSuccessors[0]->getId());
    }

    public function testShouldGetEmptyArrayAsDirectSuccessorsIfNodeDoesNotExist(): void
    {
        $nonExistentNode = new Coordinate(0, 0);

        $this->assertCount(0, $this->sut->getDirectSuccessors($nonExistentNode));
    }
}
