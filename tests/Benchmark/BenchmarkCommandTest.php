<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\BenchmarkCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class BenchmarkCommandTest extends \PHPUnit_Framework_TestCase
{
    private $benchmarkCommand;
    private $commandTester;

    protected function setUp()
    {
        $this->benchmarkCommand = new BenchmarkCommand();

        $application = new Application();
        $application->add($this->benchmarkCommand);

        $this->commandTester = new CommandTester($this->benchmarkCommand);
    }

    public function testShouldExecuteCorrectly()
    {
        $successfulExitCode = 0;

        $actualExitCode = $this->commandTester->execute(array(
            'command' => $this->benchmarkCommand->getName(),
            '--size' => array(1),
            '--iterations' => 1,
        ));

        $output = $this->commandTester->getDisplay();

        $this->assertSame($successfulExitCode, $actualExitCode);
        $this->assertContains('1x1', $output);
    }

    public function testShouldHandleInvalidInput()
    {
        $unsuccessfulExitCode = 1;
        $invalidIterationsParameter = 'foobar';

        $actualExitCode = $this->commandTester->execute(array(
            'command' => $this->benchmarkCommand->getName(),
            '--size' => array(1),
            '--iterations' => $invalidIterationsParameter,
        ));

        $output = $this->commandTester->getDisplay();

        $this->assertSame($unsuccessfulExitCode, $actualExitCode);
        $this->assertContains('[ERROR]', $output);
    }
}
