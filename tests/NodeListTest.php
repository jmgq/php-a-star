<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\Node;
use JMGQ\AStar\NodeList;
use PHPUnit\Framework\TestCase;

class NodeListTest extends TestCase
{
    private NodeList $sut;

    protected function setUp(): void
    {
        $this->sut = new NodeList();
    }

    public function testShouldBeIterable(): void
    {
        $this->assertInstanceOf('IteratorAggregate', $this->sut);
    }

    public function testShouldBeInitiallyEmpty(): void
    {
        $this->assertCount(0, $this->sut);
    }

    public function testShouldAddNodes(): void
    {
        $node1 = $this->createStub(Node::class);
        $node1->method('getID')
            ->willReturn('ID1');

        $node2 = $this->createStub(Node::class);
        $node2->method('getID')
            ->willReturn('ID2');

        $this->assertCount(0, $this->sut);

        $this->sut->add($node1);

        $this->assertCount(1, $this->sut);

        $this->sut->add($node2);

        $this->assertCount(2, $this->sut);
        $this->assertContains($node1, $this->sut);
        $this->assertContains($node2, $this->sut);
    }

    public function testShouldDetermineIfItIsEmptyOrNot(): void
    {
        $node = $this->createStub(Node::class);

        $this->assertTrue($this->sut->isEmpty());

        $this->sut->add($node);

        $this->assertFalse($this->sut->isEmpty());
    }

    public function testShouldOverwriteIdenticalNodes(): void
    {
        $uniqueID = 'someUniqueID';

        $node1 = $this->createStub(Node::class);
        $node1->method('getID')
            ->willReturn($uniqueID);

        $node2 = $this->createStub(Node::class);
        $node2->method('getID')
            ->willReturn($uniqueID);

        $this->sut->add($node1);

        $this->assertCount(1, $this->sut);

        $this->sut->add($node2);

        $this->assertCount(1, $this->sut);

        foreach ($this->sut as $node) {
            $this->assertSame($node2, $node);
        }
    }

    public function testShouldCheckIfItContainsANode(): void
    {
        $node = $this->createStub(Node::class);
        $node->method('getID')
            ->willReturn('someUniqueID');

        $this->assertFalse($this->sut->contains($node));

        $this->sut->add($node);

        $this->assertTrue($this->sut->contains($node));
    }

    public function testShouldExtractBestNode(): void
    {
        $bestNode = $this->createStub(Node::class);
        $bestNode->method('getID')
            ->willReturn('bestNode');
        $bestNode->method('getF')
            ->willReturn(1);

        $mediumNode = $this->createStub(Node::class);
        $mediumNode->method('getID')
            ->willReturn('mediumNode');
        $mediumNode->method('getF')
            ->willReturn(3);

        $worstNode = $this->createStub(Node::class);
        $worstNode->method('getID')
            ->willReturn('worstNode');
        $worstNode->method('getF')
            ->willReturn(10);

        $this->sut->add($mediumNode);
        $this->sut->add($bestNode);
        $this->sut->add($worstNode);

        $this->assertCount(3, $this->sut);

        $extractedNode = $this->sut->extractBest();

        $this->assertSame($bestNode, $extractedNode);
        $this->assertCount(2, $this->sut);
        $this->assertNotContains($extractedNode, $this->sut);
    }

    public function testShouldRemoveNode(): void
    {
        $nodeToBeRemoved = $this->createStub(Node::class);
        $nodeToBeRemoved->method('getID')
            ->willReturn('nodeToBeRemoved');

        $nodeToBeKept = $this->createStub(Node::class);
        $nodeToBeKept->method('getID')
            ->willReturn('nodeToBeKept');

        $this->sut->add($nodeToBeRemoved);
        $this->sut->add($nodeToBeKept);

        $this->assertCount(2, $this->sut);

        $this->sut->remove($nodeToBeRemoved);

        $this->assertCount(1, $this->sut);
        $this->assertNotContains($nodeToBeRemoved, $this->sut);
        $this->assertContains($nodeToBeKept, $this->sut);
    }

    public function testShouldGetNode(): void
    {
        $node = $this->createStub(Node::class);
        $node->method('getID')
            ->willReturn('someUniqueID');

        $this->sut->add($node);

        $this->assertSame($node, $this->sut->get($node));
    }

    public function testShouldGetNullIfNodeNotFound(): void
    {
        $nonExistentNode = $this->createStub(Node::class);
        $nonExistentNode->method('getID')
            ->willReturn('someUniqueID');

        $this->assertNull($this->sut->get($nonExistentNode));
    }

    public function testShouldEmptyTheList(): void
    {
        $node = $this->createStub(Node::class);
        $node->method('getID')
            ->willReturn('someUniqueID');

        $this->sut->add($node);

        $this->assertCount(1, $this->sut);

        $this->sut->clear();

        $this->assertCount(0, $this->sut);
    }
}
