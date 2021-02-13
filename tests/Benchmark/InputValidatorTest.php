<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\InputValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

class InputValidatorTest extends TestCase
{
    private InputValidator $sut;
    /** @var MockObject & InputInterface */
    private MockObject | InputInterface $input;
    /** @var MockObject & StyleInterface */
    private MockObject | StyleInterface $output;

    /**
     * @return mixed[][]
     */
    public function invalidNaturalNumberProvider(): array
    {
        return [
            [0],
            [-1],
            [2.5],
            [null],
            [[]],
            ['foo'],
        ];
    }

    /**
     * @return mixed[][]
     */
    public function invalidOptionalIntegerProvider(): array
    {
        return [
            ['a'],
            [[]],
            [false],
            [1.5],
            [-1.5],
            [''],
            [' '],
        ];
    }

    protected function setUp(): void
    {
        $this->input = $this->createMock(InputInterface::class);

        $this->output = $this->createMock(StyleInterface::class);

        $this->sut = new InputValidator($this->output);
    }

    public function testShouldValidateCorrectValues(): void
    {
        $validSizes = ['5', '10'];
        $validIterations = '15';
        $validSeed = '123456';

        $this->setInputExpectations($validSizes, $validIterations, $validSeed);

        $this->output->expects($this->never())
            ->method($this->anything());

        $result = $this->sut->validate($this->input);

        $this->assertTrue($result);
    }

    public function testShouldNotValidateIncorrectValues(): void
    {
        $invalidSizes = ['a'];
        $invalidIterations = 'b';
        $invalidSeed = 'c';

        $this->setInputExpectations($invalidSizes, $invalidIterations, $invalidSeed);

        $this->output->expects($this->exactly(3))
            ->method('error')
            ->withConsecutive(
                ['The size must be an integer greater than 0'],
                ['The number of iterations must be an integer greater than 0'],
                ['The seed must be an integer'],
            );

        $result = $this->sut->validate($this->input);

        $this->assertFalse($result);
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     */
    public function testShouldNotValidateIncorrectSizes(mixed $invalidSize): void
    {
        $invalidSizes = [$invalidSize, '10', $invalidSize];
        $validIterations = '15';
        $validSeed = '123456';

        $this->setInputExpectations($invalidSizes, $validIterations, $validSeed);

        $this->output->expects($this->exactly(2))
            ->method('error')
            ->with('The size must be an integer greater than 0');

        $result = $this->sut->validate($this->input);

        $this->assertFalse($result);
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     */
    public function testShouldNotValidateIncorrectIterations(mixed $invalidIterations): void
    {
        $validSizes = ['8'];
        $validSeed = '123456';

        $this->setInputExpectations($validSizes, $invalidIterations, $validSeed);

        $this->output->expects($this->once())
            ->method('error')
            ->with('The number of iterations must be an integer greater than 0');

        $result = $this->sut->validate($this->input);

        $this->assertFalse($result);
    }

    /**
     * @dataProvider invalidOptionalIntegerProvider
     */
    public function testShouldNotValidateIncorrectSeed(mixed $invalidSeed): void
    {
        $validSizes = ['8'];
        $validIterations = '15';

        $this->setInputExpectations($validSizes, $validIterations, $invalidSeed);

        $this->output->expects($this->once())
            ->method('error')
            ->with('The seed must be an integer');

        $result = $this->sut->validate($this->input);

        $this->assertFalse($result);
    }

    public function testShouldValidateOptionalSeed(): void
    {
        $validSizes = ['8'];
        $validIterations = '15';
        $validOptionalSeed = null;

        $this->setInputExpectations($validSizes, $validIterations, $validOptionalSeed);

        $this->output->expects($this->never())
            ->method($this->anything());

        $result = $this->sut->validate($this->input);

        $this->assertTrue($result);
    }

    /**
     * @param mixed[] $sizes
     * @param mixed $iterations
     * @param mixed $seed
     */
    private function setInputExpectations(array $sizes, mixed $iterations, mixed $seed): void
    {
        $this->input->expects($this->exactly(3))
            ->method('getOption')
            ->willReturnMap([
                ['size', $sizes],
                ['iterations', $iterations],
                ['seed', $seed],
            ]);
    }
}
