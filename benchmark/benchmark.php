#!/usr/bin/env php
<?php

namespace JMGQ\AStar\Benchmark;

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$name = 'A-Star Benchmark';
$version = '0.3.0';

$application = new Application($name, $version);

$benchmarkCommand = new BenchmarkCommand();

$application->add($benchmarkCommand);
$application->setDefaultCommand((string) $benchmarkCommand->getName());

$application->run();
