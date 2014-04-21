<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\NodeList;

class NodeListTest extends \PHPUnit_Framework_TestCase
{
    /** @var NodeList */
    private $sut;

    public function setUp()
    {
        $this->sut = new NodeList();
    }

    public function testShouldBeIterable()
    {
        $this->assertInstanceOf('IteratorAggregate', $this->sut);
    }

    public function testShouldBeInitiallyEmpty()
    {
        $this->assertCount(0, $this->sut);
    }

    public function testShouldAddNodes()
    {
        $node1 = $this->getMock('JMGQ\AStar\Node');
        $node1->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('ID1'));

        $node2 = $this->getMock('JMGQ\AStar\Node');
        $node2->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('ID2'));

        $this->assertCount(0, $this->sut);

        $this->sut->add($node1);

        $this->assertCount(1, $this->sut);

        $this->sut->add($node2);

        $this->assertCount(2, $this->sut);
        $this->assertContains($node1, $this->sut);
        $this->assertContains($node2, $this->sut);
    }

    public function testShouldDetermineIfItIsEmptyOrNot()
    {
        $node = $this->getMock('JMGQ\AStar\Node');

        $this->assertTrue($this->sut->isEmpty());

        $this->sut->add($node);

        $this->assertFalse($this->sut->isEmpty());
    }

    public function testShouldOverwriteIdenticalNodes()
    {
        $uniqueID = 'someUniqueID';

        $node1 = $this->getMock('JMGQ\AStar\Node');
        $node1->expects($this->any())
            ->method('getID')
            ->will($this->returnValue($uniqueID));

        $node2 = $this->getMock('JMGQ\AStar\Node');
        $node2->expects($this->any())
            ->method('getID')
            ->will($this->returnValue($uniqueID));

        $this->sut->add($node1);

        $this->assertCount(1, $this->sut);

        $this->sut->add($node2);

        $this->assertCount(1, $this->sut);

        foreach ($this->sut as $node) {
            $this->assertSame($node2, $node);
        }
    }

    public function testShouldCheckIfItContainsANode()
    {
        $node = $this->getMock('JMGQ\AStar\Node');
        $node->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('someUniqueID'));

        $this->assertFalse($this->sut->contains($node));

        $this->sut->add($node);

        $this->assertTrue($this->sut->contains($node));
    }

    public function testShouldExtractBestNode()
    {
        $bestNode = $this->getMock('JMGQ\AStar\Node');
        $bestNode->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('bestNode'));
        $bestNode->expects($this->any())
            ->method('getF')
            ->will($this->returnValue(1));

        $mediumNode = $this->getMock('JMGQ\AStar\Node');
        $mediumNode->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('mediumNode'));
        $mediumNode->expects($this->any())
            ->method('getF')
            ->will($this->returnValue(3));

        $worstNode = $this->getMock('JMGQ\AStar\Node');
        $worstNode->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('worstNode'));
        $worstNode->expects($this->any())
            ->method('getF')
            ->will($this->returnValue(10));

        $this->sut->add($mediumNode);
        $this->sut->add($bestNode);
        $this->sut->add($worstNode);

        $this->assertCount(3, $this->sut);

        $extractedNode = $this->sut->extractBest();

        $this->assertSame($bestNode, $extractedNode);
        $this->assertCount(2, $this->sut);
        $this->assertNotContains($extractedNode, $this->sut);
    }

    public function testShouldRemoveNode()
    {
        $nodeToBeRemoved = $this->getMock('JMGQ\AStar\Node');
        $nodeToBeRemoved->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('nodeToBeRemoved'));

        $nodeToBeKept = $this->getMock('JMGQ\AStar\Node');
        $nodeToBeKept->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('nodeToBeKept'));

        $this->sut->add($nodeToBeRemoved);
        $this->sut->add($nodeToBeKept);

        $this->assertCount(2, $this->sut);

        $this->sut->remove($nodeToBeRemoved);

        $this->assertCount(1, $this->sut);
        $this->assertNotContains($nodeToBeRemoved, $this->sut);
        $this->assertContains($nodeToBeKept, $this->sut);
    }

    public function testShouldGetNode()
    {
        $node = $this->getMock('JMGQ\AStar\Node');
        $node->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('someUniqueID'));

        $this->sut->add($node);

        $this->assertSame($node, $this->sut->get($node));
    }

    public function testShouldGetNullIfNodeNotFound()
    {
        $nonExistentNode = $this->getMock('JMGQ\AStar\Node');
        $nonExistentNode->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('someUniqueID'));

        $this->assertNull($this->sut->get($nonExistentNode));
    }

    public function testShouldEmptyTheList()
    {
        $node = $this->getMock('JMGQ\AStar\Node');
        $node->expects($this->any())
            ->method('getID')
            ->will($this->returnValue('someUniqueID'));

        $this->sut->add($node);

        $this->assertCount(1, $this->sut);

        $this->sut->clear();

        $this->assertCount(0, $this->sut);
    }
}
