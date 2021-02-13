<?php

namespace JMGQ\AStar\Benchmark;

use JMGQ\AStar\Benchmark\Result\Result;
use JMGQ\AStar\Benchmark\Result\ResultAggregator;
use JMGQ\AStar\Benchmark\Result\ResultPrinter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BenchmarkCommand extends Command
{
    public const SIZE_OPTION = 'size';
    public const ITERATIONS_OPTION = 'iterations';
    public const SEED_OPTION = 'seed';

    private const SUCCESS_EXIT_CODE = 0;
    private const ERROR_EXIT_CODE = 1;

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $styledOutput = new SymfonyStyle($input, $output);

        $inputValidator = new InputValidator($styledOutput);
        $hasValidInput = $inputValidator->validate($input);

        if (!$hasValidInput) {
            return self::ERROR_EXIT_CODE;
        }

        /** @var int[] $sizes */
        $sizes = $input->getOption(self::SIZE_OPTION);
        /** @var int $iterations */
        $iterations = $input->getOption(self::ITERATIONS_OPTION);
        /** @var int | null $seed */
        $seed = $input->getOption(self::SEED_OPTION);

        $progressBar = new SymfonyProgressBar($styledOutput->createProgressBar());
        $benchmarkRunner = new BenchmarkRunner($progressBar);

        $results = $benchmarkRunner->run($sizes, $iterations, $seed);

        $this->printResults($results, $styledOutput);

        return self::SUCCESS_EXIT_CODE;
    }

    private function addSizeOption(): BenchmarkCommand
    {
        $description = 'Number of rows and columns of the terrain';
        $defaultValue = ['5', '10', '15', '20', '25'];

        $this->addOption(
            self::SIZE_OPTION,
            's',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            $description,
            $defaultValue
        );

        return $this;
    }

    private function addIterationsOption(): BenchmarkCommand
    {
        $description = 'Number of times the algorithm will run against each terrain';
        $defaultValue = 10;

        $this->addOption(self::ITERATIONS_OPTION, 'i', InputOption::VALUE_REQUIRED, $description, $defaultValue);

        return $this;
    }

    private function addSeedOption(): BenchmarkCommand
    {
        $description = 'Integer used to generate random costs. Set the same value in order to replicate an execution';
        $defaultValue = null;

        $this->addOption(self::SEED_OPTION, 'e', InputOption::VALUE_REQUIRED, $description, $defaultValue);

        return $this;
    }

    /**
     * @param Result[] $results
     * @param StyleInterface $output
     */
    private function printResults(array $results, StyleInterface $output): void
    {
        $output->newLine();

        $resultAggregator = new ResultAggregator();
        $aggregatedResults = $resultAggregator->process($results);

        $resultPrinter = new ResultPrinter($output);
        $resultPrinter->display($aggregatedResults);
    }
}
