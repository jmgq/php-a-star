<?php

namespace JMGQ\AStar\Benchmark;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BenchmarkCommand extends Command
{
    const SIZE_OPTION = 'size';
    const ITERATIONS_OPTION = 'iterations';
    const SEED_OPTION = 'seed';

    const SUCCESS_EXIT_CODE = 0;
    const ERROR_EXIT_CODE = 1;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $name = 'benchmark';
        $description = 'Runs a benchmark using the Terrain example';
        $help = 'This commands allows you to run a benchmark using the Terrain example';

        $this->setName($name)
            ->setDescription($description)
            ->setHelp($help)
            ->addSizeOption()
            ->addIterationsOption()
            ->addSeedOption();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $styledOutput = new SymfonyStyle($input, $output);

        $inputValidator = new InputValidator($styledOutput);
        $hasValidInput = $inputValidator->validate($input);

        if (!$hasValidInput) {
            return self::ERROR_EXIT_CODE;
        }

        $sizes = $input->getOption(self::SIZE_OPTION);
        $iterations = $input->getOption(self::ITERATIONS_OPTION);
        $seed = $input->getOption(self::SEED_OPTION);

        $progressBar = $styledOutput->createProgressBar();
        $benchmarkRunner = new BenchmarkRunner($progressBar);

        $results = $benchmarkRunner->run($sizes, $iterations, $seed);

        $resultPrinter = new ResultPrinter($styledOutput);
        $resultPrinter->display($results);

        return self::SUCCESS_EXIT_CODE;
    }

    private function addSizeOption()
    {
        $description = 'Number of rows and columns of the terrain';
        $defaultValue = array(5, 10, 15, 20, 25);

        $this->addOption(
            self::SIZE_OPTION,
            's',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            $description,
            $defaultValue
        );

        return $this;
    }

    private function addIterationsOption()
    {
        $description = 'Number of times the algorithm will run against each terrain';
        $defaultValue = 10;

        $this->addOption(self::ITERATIONS_OPTION, 'i', InputOption::VALUE_REQUIRED, $description, $defaultValue);

        return $this;
    }

    private function addSeedOption()
    {
        $description = 'Integer used to generate random costs. Set the same value in order to replicate an execution';
        $defaultValue = null;

        $this->addOption(self::SEED_OPTION, 'e', InputOption::VALUE_REQUIRED, $description, $defaultValue);

        return $this;
    }
}
