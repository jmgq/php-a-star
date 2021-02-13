<?php

namespace JMGQ\AStar\Tests\Example\Terrain;

use JMGQ\AStar\Example\Terrain\Position;
use JMGQ\AStar\Example\Terrain\SequencePrinter;
use JMGQ\AStar\Example\Terrain\TerrainCost;
use PHPUnit\Framework\TestCase;

class SequencePrinterTest extends TestCase
{
    private SequencePrinter $sut;

    /**
     * @return string[][]
     */
    public function validStringProvider(): array
    {
        return [
            ['foo'],
            ['foo bar'],
            [''],
            ['-'],
            [' '],
        ];
    }

    /**
     * @return mixed[][]
     */
    public function validNaturalNumberProvider(): array
    {
        return [
            [1],
            [3],
            [PHP_INT_MAX],
            ['5'],
        ];
    }

    /**
     * @return mixed[][]
     */
    public function invalidNaturalNumberForTileProvider(): array
    {
        return [
            [0, \InvalidArgumentException::class, 'Invalid tile size'],
            [-1, \InvalidArgumentException::class, 'Invalid tile size'],
            [null, \TypeError::class, 'must be of type int'],
            [[], \TypeError::class, 'must be of type int'],
            ['foo', \TypeError::class, 'must be of type int'],
        ];
    }

    protected function setUp(): void
    {
        $terrainCost = new TerrainCost([
            [4, 5, 1, 2, 3],
            [2, 8, 5, 1, 1],
            [1, 3, 3, 3, 4],
            [1, 9, 2, 3, 3],
            [2, 4, 6, 8, 1],
            [1, 3, 5, 7, 9],
        ]);

        $sequence = [
            new Position(2, 0),
            new Position(3, 1),
            new Position(4, 2),
            new Position(3, 2),
            new Position(2, 2),
            new Position(1, 2),
            new Position(0, 2),
            new Position(1, 3),
            new Position(2, 4),
            new Position(3, 4),
        ];

        $this->sut = new SequencePrinter($terrainCost, $sequence);
    }

    public function testShouldHaveDefaultEmptyTileToken(): void
    {
        $defaultEmptyTileToken = '-';

        $this->assertSame($defaultEmptyTileToken, $this->sut->getEmptyTileToken());
    }

    /**
     * @dataProvider validStringProvider
     */
    public function testShouldSetValidEmptyTileToken(string $token): void
    {
        $this->sut->setEmptyTileToken($token);

        $this->assertSame($token, $this->sut->getEmptyTileToken());
    }

    public function testShouldHaveDefaultTileSize(): void
    {
        $defaultTileSize = 3;

        $this->assertSame($defaultTileSize, $this->sut->getTileSize());
    }

    /**
     * @dataProvider validNaturalNumberProvider
     */
    public function testShouldSetValidTileSize(mixed $tileSize): void
    {
        $expectedTileSize = (int) $tileSize;

        $this->sut->setTileSize($tileSize);

        $this->assertSame($expectedTileSize, $this->sut->getTileSize());
    }

    /**
     * @dataProvider invalidNaturalNumberForTileProvider
     * @param mixed $invalidTileSize
     * @param class-string<\Throwable> $expectedException
     * @param string $expectedExceptionMessage
     */
    public function testShouldNotSetInvalidTileSize(
        mixed $invalidTileSize,
        string $expectedException,
        string $expectedExceptionMessage
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $this->sut->setTileSize($invalidTileSize);
    }

    public function testShouldHaveDefaultPadToken(): void
    {
        $defaultPadToken = ' ';

        $this->assertSame($defaultPadToken, $this->sut->getPadToken());
    }

    /**
     * @dataProvider validStringProvider
     */
    public function testShouldSetValidPadToken(string $token): void
    {
        $this->sut->setPadToken($token);

        $this->assertSame($token, $this->sut->getPadToken());
    }

    public function testShouldPrintANodeSequence(): void
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

    public function testShouldPrintANodeSequenceWithNonDefaultValues(): void
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
