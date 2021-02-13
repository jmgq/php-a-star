<?php

namespace JMGQ\AStar\Tests\Benchmark\Result;

use JMGQ\AStar\Benchmark\Result\Result;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    /**
     * @return mixed[][]
     */
    public function validValuesProvider(): array
    {
        return [
            [1, PHP_INT_MAX, true],
            ['4', '2', false],
            [20, 0, true],
        ];
    }

    /**
     * @return mixed[][]
     */
    public function invalidNaturalNumberProvider(): array
    {
        return [
            [0, \InvalidArgumentException::class, 'Invalid'],
            [-1, \InvalidArgumentException::class, 'Invalid'],
            [null, \TypeError::class, 'must be of type int'],
            [[], \TypeError::class, 'must be of type int'],
            ['foo', \TypeError::class, 'must be of type int'],
        ];
    }

    /**
     * @return mixed[][]
     */
    public function invalidNonNegativeIntegerProvider(): array
    {
        return [
            [-1, \InvalidArgumentException::class, 'Invalid'],
            [null, \TypeError::class, 'must be of type int'],
            ['a', \TypeError::class, 'must be of type int'],
            [[], \TypeError::class, 'must be of type int'],
        ];
    }

    /**
     * @dataProvider validValuesProvider
     */
    public function testShouldSetValidValues(mixed $size, mixed $duration, bool $hasSolution): void
    {
        $expectedSize = (int) $size;
        $expectedDuration = (int) $duration;

        $sut = new Result($size, $duration, $hasSolution);

        $this->assertSame($expectedSize, $sut->getSize());
        $this->assertSame($expectedDuration, $sut->getDuration());
        $this->assertSame($hasSolution, $sut->hasSolution());
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     * @param mixed $invalidSize
     * @param class-string<\Throwable> $expectedException
     * @param string $expectedExceptionMessage
     */
    public function testShouldNotSetInvalidSize(
        mixed $invalidSize,
        string $expectedException,
        string $expectedExceptionMessage
    ): void {
        $validDuration = 200;
        $validHasSolution = true;

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);

        new Result($invalidSize, $validDuration, $validHasSolution);
    }

    /**
     * @dataProvider invalidNonNegativeIntegerProvider
     * @param mixed $invalidDuration
     * @param class-string<\Throwable> $expectedException
     * @param string $expectedExceptionMessage
     */
    public function testShouldNotSetInvalidDuration(
        mixed $invalidDuration,
        string $expectedException,
        string $expectedExceptionMessage
    ): void {
        $validSize = '5';
        $validHasSolution = false;

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);

        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress InvalidScalarArgument
         * A numeric string for the size is a valid user input
         */
        new Result($validSize, $invalidDuration, $validHasSolution);
    }

    public function testShouldReturnABooleanTypeWhenRetrievingHasSolution(): void
    {
        $size = 2;
        $duration = 3;
        $hasSolution = 1;

        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress InvalidScalarArgument
         * We actually want to pass an integer for $hasSolution as part of this test
         */
        $sut = new Result($size, $duration, $hasSolution);

        $this->assertTrue($sut->hasSolution());
    }
}
