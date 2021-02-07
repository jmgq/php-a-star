<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\BenchmarkCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class BenchmarkCommandTest extends TestCase
{
    private BenchmarkCommand $benchmarkCommand;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->benchmarkCommand = new BenchmarkCommand();

        $application = new Application();
        $application->add($this->benchmarkCommand);

        $this->commandTester = new CommandTester($this->benchmarkCommand);
    }

    public function testShouldExecuteCorrectly(): void
    {
        $successfulExitCode = 0;

        $actualExitCode = $this->commandTester->execute([
            'command' => $this->benchmarkCommand->getName(),
            '--size' => [1],
            '--iterations' => 1,
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertSame($successfulExitCode, $actualExitCode);
        $this->assertStringContainsString('1x1', $output);
    }

    public function testShouldHandleInvalidInput(): void
    {
        $unsuccessfulExitCode = 1;
        $invalidIterationsParameter = 'foobar';

        $actualExitCode = $this->commandTester->execute([
            'command' => $this->benchmarkCommand->getName(),
            '--size' => [1],
            '--iterations' => $invalidIterationsParameter,
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertSame($unsuccessfulExitCode, $actualExitCode);
        $this->assertStringContainsString('[ERROR]', $output);
    }
}
