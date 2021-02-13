<?php

namespace JMGQ\AStar\Tests\Node\Collection;

use JMGQ\AStar\Node\Collection\NodeCollectionInterface;
use JMGQ\AStar\Node\Collection\NodeHashTable;
use JMGQ\AStar\Node\Node;
use PHPUnit\Framework\TestCase;

class NodeHashTableTest extends TestCase
{
    /** @var NodeHashTable<mixed> */
    private NodeHashTable $sut;

    protected function setUp(): void
    {
        $this->sut = new NodeHashTable();
    }

    public function testShouldBeACollectionOfNodes(): void
    {
        $this->assertInstanceOf(NodeCollectionInterface::class, $this->sut);
    }

    public function testShouldBeIterable(): void
    {
        $this->assertIsIterable($this->sut);
    }

    public function testShouldBeInitiallyEmpty(): void
    {
        $this->assertCount(0, $this->sut);
    }

    public function testShouldAddNodes(): void
    {
        $node1 = $this->createStub(Node::class);
        $node1->method('getId')
            ->willReturn('Id1');

        $node2 = $this->createStub(Node::class);
        $node2->method('getId')
            ->willReturn('Id2');

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
        $uniqueId = 'someUniqueId';

        $node1 = $this->createStub(Node::class);
        $node1->method('getId')
            ->willReturn($uniqueId);

        $node2 = $this->createStub(Node::class);
        $node2->method('getId')
            ->willReturn($uniqueId);

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
        $node->method('getId')
            ->willReturn('someUniqueId');

        $this->assertNotContains($node, $this->sut);
        $this->assertFalse($this->sut->contains($node));

        $this->sut->add($node);

        $this->assertContains($node, $this->sut);
        $this->assertTrue($this->sut->contains($node));
    }

    public function testShouldExtractBestNode(): void
    {
        $bestNode = $this->createStub(Node::class);
        $bestNode->method('getId')
            ->willReturn('bestNode');
        $bestNode->method('getF')
            ->willReturn(1);

        $mediumNode = $this->createStub(Node::class);
        $mediumNode->method('getId')
            ->willReturn('mediumNode');
        $mediumNode->method('getF')
            ->willReturn(3);

        $worstNode = $this->createStub(Node::class);
        $worstNode->method('getId')
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
        $nodeToBeRemoved->method('getId')
            ->willReturn('nodeToBeRemoved');

        $nodeToBeKept = $this->createStub(Node::class);
        $nodeToBeKept->method('getId')
            ->willReturn('nodeToBeKept');

        $this->sut->add($nodeToBeRemoved);
        $this->sut->add($nodeToBeKept);

        $this->assertCount(2, $this->sut);

        $this->sut->remove($nodeToBeRemoved);

        $this->assertCount(1, $this->sut);
        $this->assertNotContains($nodeToBeRemoved, $this->sut);
        $this->assertContains($nodeToBeKept, $this->sut);
    }

    public function testShouldGetNodeById(): void
    {
        $nodeId = 'someUniqueId';

        $node = $this->createStub(Node::class);
        $node->method('getId')
            ->willReturn($nodeId);

        $this->sut->add($node);

        $this->assertSame($node, $this->sut->get($nodeId));
    }

    public function testShouldGetNullIfNodeNotFound(): void
    {
        $nonExistentNodeId = 'foo';

        $this->assertNull($this->sut->get($nonExistentNodeId));
    }

    public function testShouldEmptyTheList(): void
    {
        $node = $this->createStub(Node::class);
        $node->method('getId')
            ->willReturn('someUniqueId');

        $this->sut->add($node);

        $this->assertCount(1, $this->sut);

        $this->sut->clear();

        $this->assertCount(0, $this->sut);
    }
}
