<?php

namespace JMGQ\AStar\Tests\Node;

use JMGQ\AStar\Node\Node;
use JMGQ\AStar\Node\NodeIdentifierInterface;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    private Node $sut;
    private string $mockState = 'foobar';

    public function validNumberProvider(): array
    {
        return [
            [1],
            [1.5],
            ['1.5'],
            ['200'],
            [0],
            [PHP_INT_MAX]
        ];
    }

    public function invalidNumberProvider(): array
    {
        return [
            ['a'],
            [[]],
            [null],
            [''],
            [' ']
        ];
    }

    protected function setUp(): void
    {
        $this->sut = new Node($this->mockState);
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

    public function testShouldSetState(): void
    {
        $this->assertSame($this->mockState, $this->sut->getState());
    }

    public function testShouldSetItsIdToTheOneProvidedByTheUser(): void
    {
        $uniqueNodeId = 'some-unique-id';

        $mockStateWithId = $this->createMock(NodeIdentifierInterface::class);
        $mockStateWithId->expects($this->once())
            ->method('getUniqueNodeId')
            ->willReturn($uniqueNodeId);

        $this->sut = new Node($mockStateWithId);

        $this->assertSame($uniqueNodeId, $this->sut->getId());
    }

    public function testShouldSetItsIdToTheSerialisedStateIfTheUserDoesNotProvideAnId(): void
    {
        $expectedId = serialize($this->mockState);

        $this->assertSame($expectedId, $this->sut->getId());
    }

    /**
     * @dataProvider validNumberProvider
     */
    public function testShouldSetValidG($validScore): void
    {
        $this->sut->setG($validScore);

        $actualScore = $this->sut->getG();

        $this->assertIsNumeric($actualScore);
        $this->assertEquals($validScore, $actualScore);
    }

    /**
     * @dataProvider invalidNumberProvider
     */
    public function testShouldNotSetInvalidG($invalidScore): void
    {
        $this->expectException(\TypeError::class);

        $this->sut->setG($invalidScore);
    }

    /**
     * @dataProvider validNumberProvider
     */
    public function testShouldSetValidH($validScore): void
    {
        $this->sut->setH($validScore);

        $actualScore = $this->sut->getH();

        $this->assertIsNumeric($actualScore);
        $this->assertEquals($validScore, $actualScore);
    }

    /**
     * @dataProvider invalidNumberProvider
     */
    public function testShouldNotSetInvalidH($invalidScore): void
    {
        $this->expectException(\TypeError::class);

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
