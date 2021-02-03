<?php

namespace JMGQ\AStar\Tests\Example\Terrain;

use JMGQ\AStar\Example\Terrain\MyNode;
use JMGQ\AStar\Example\Terrain\SequencePrinter;
use JMGQ\AStar\Example\Terrain\TerrainCost;

class SequencePrinterTest extends \PHPUnit_Framework_TestCase
{
    /** @var SequencePrinter */
    private $sut;

    public function validStringProvider()
    {
        return array(
            array('foo'),
            array('foo bar'),
            array(''),
            array('-'),
            array(' ')
        );
    }

    public function invalidStringProvider()
    {
        return array(
            array(1),
            array(false),
            array(null),
            array(3.2)
        );
    }

    public function validNaturalNumberProvider()
    {
        return array(
            array(1),
            array(3),
            array(PHP_INT_MAX),
            array('5')
        );
    }

    public function invalidNaturalNumberProvider()
    {
        return array(
            array(0),
            array(-1),
            array(2.5),
            array(null),
            array(array()),
            array('foo')
        );
    }

    protected function setUp()
    {
        $terrainCost = new TerrainCost(
            array(
                array(4, 5, 1, 2, 3),
                array(2, 8, 5, 1, 1),
                array(1, 3, 3, 3, 4),
                array(1, 9, 2, 3, 3),
                array(2, 4, 6, 8, 1),
                array(1, 3, 5, 7, 9)
            )
        );

        $sequence = array(
            new MyNode(2, 0),
            new MyNode(3, 1),
            new MyNode(4, 2),
            new MyNode(3, 2),
            new MyNode(2, 2),
            new MyNode(1, 2),
            new MyNode(0, 2),
            new MyNode(1, 3),
            new MyNode(2, 4),
            new MyNode(3, 4)
        );

        $this->sut = new SequencePrinter($terrainCost, $sequence);
    }

    public function testShouldHaveDefaultEmptyTileToken()
    {
        $defaultEmptyTileToken = '-';

        $this->assertSame($defaultEmptyTileToken, $this->sut->getEmptyTileToken());
    }

    /**
     * @dataProvider validStringProvider
     */
    public function testShouldSetValidEmptyTileToken($token)
    {
        $this->sut->setEmptyTileToken($token);

        $this->assertSame($token, $this->sut->getEmptyTileToken());
    }

    /**
     * @dataProvider invalidStringProvider
     * @expectedException \InvalidArgumentException
     */
    public function testShouldNotSetInvalidEmptyFileToken($invalidToken)
    {
        $this->sut->setEmptyTileToken($invalidToken);
    }

    public function testShouldHaveDefaultTileSize()
    {
        $defaultTileSize = 3;

        $this->assertSame($defaultTileSize, $this->sut->getTileSize());
    }

    /**
     * @dataProvider validNaturalNumberProvider
     */
    public function testShouldSetValidTileSize($tileSize)
    {
        $expectedTileSize = (int) $tileSize;

        $this->sut->setTileSize($tileSize);

        $this->assertSame($expectedTileSize, $this->sut->getTileSize());
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     * @expectedException \InvalidArgumentException
     */
    public function testShouldNotSetInvalidTileSize($invalidTileSize)
    {
        $this->sut->setTileSize($invalidTileSize);
    }

    public function testShouldHaveDefaultPadToken()
    {
        $defaultPadToken = ' ';

        $this->assertSame($defaultPadToken, $this->sut->getPadToken());
    }

    /**
     * @dataProvider validStringProvider
     */
    public function testShouldSetValidPadToken($token)
    {
        $this->sut->setPadToken($token);

        $this->assertSame($token, $this->sut->getPadToken());
    }

    /**
     * @dataProvider invalidStringProvider
     * @expectedException \InvalidArgumentException
     */
    public function testShouldNotSetInvalidPadToken($invalidToken)
    {
        $this->sut->setPadToken($invalidToken);
    }

    public function testShouldPrintANodeSequence()
    {
        $expectedOutput = <<<HEREDOC
  -  -  7  -  -
  -  -  6  8  -
  1  -  5  -  9
  -  2  4  - 10
  -  -  3  -  -
  -  -  -  -  -
HEREDOC;

        $this->sut->printSequence();

        $this->expectOutputString($expectedOutput);
    }

    public function testShouldPrintANodeSequenceWithNonDefaultValues()
    {
        $padToken = 'x';
        $emptyTileToken = 'o';
        $tileSize = 5;

        $expectedOutput = <<<HEREDOC
xxxxoxxxxoxxxx7xxxxoxxxxo
xxxxoxxxxoxxxx6xxxx8xxxxo
xxxx1xxxxoxxxx5xxxxoxxxx9
xxxxoxxxx2xxxx4xxxxoxxx10
xxxxoxxxxoxxxx3xxxxoxxxxo
xxxxoxxxxoxxxxoxxxxoxxxxo
HEREDOC;

        $this->sut->setPadToken($padToken);
        $this->sut->setEmptyTileToken($emptyTileToken);
        $this->sut->setTileSize($tileSize);

        $this->sut->printSequence();

        $this->expectOutputString($expectedOutput);
    }
}
