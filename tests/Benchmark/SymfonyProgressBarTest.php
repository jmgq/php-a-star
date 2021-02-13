<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\ProgressBarInterface;
use JMGQ\AStar\Benchmark\SymfonyProgressBar;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class SymfonyProgressBarTest extends TestCase
{
    private SymfonyProgressBar $sut;
    /** @var MockObject & OutputInterface */
    private MockObject | OutputInterface $output;

    protected function setUp(): void
    {
        $outputFormatter = $this->createStub(OutputFormatterInterface::class);
        $outputFormatter->method('isDecorated')
            ->willReturn(false);

        $this->output = $this->createMock(OutputInterface::class);
        $this->output->method('getFormatter')
            ->willReturn($outputFormatter);

        // The Symfony Progress Bar cannot be mocked as it is a final class and it doesn't implement an interface
        $symfonyProgressBar = new ProgressBar($this->output);

        $this->sut = new SymfonyProgressBar($symfonyProgressBar);
    }

    public function testShouldImplementTheProgressBarInterface(): void
    {
        $this->assertInstanceOf(ProgressBarInterface::class, $this->sut);
    }

    public function testShouldDelegateTheStartMethod(): void
    {
        $this->output->expects($this->atLeastOnce())
            ->method('write');

        $this->sut->start(5);
    }

    public function testShouldDelegateTheAdvanceMethod(): void
    {
        $this->output->expects($this->atLeastOnce())
            ->method('write');

        $this->sut->advance();
    }

    public function testShouldDelegateTheFinishMethod(): void
    {
        $this->output->expects($this->never())
            ->method('write');

        $this->sut->finish();
    }
}
