#!/usr/bin/env php
<?php

namespace JMGQ\AStar\Benchmark;

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$name = 'A-Star Benchmark';
$version = '0.1.0';

$application = new Application($name, $version);

$benchmarkCommand = new BenchmarkCommand();

$application->add($benchmarkCommand);
$application->setDefaultCommand($benchmarkCommand->getName());

$application->run();
