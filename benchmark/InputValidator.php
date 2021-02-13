<?php

namespace JMGQ\AStar\Benchmark;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

class InputValidator
{
    public function __construct(private StyleInterface $output)
    {
    }

    public function validate(InputInterface $input): bool
    {
        $hasValidInput = true;

        $sizes = (array) $input->getOption(BenchmarkCommand::SIZE_OPTION);
        $iterations = $input->getOption(BenchmarkCommand::ITERATIONS_OPTION);
        $seed = $input->getOption(BenchmarkCommand::SEED_OPTION);

        foreach ($sizes as $size) {
            if (!$this->isPositiveInteger($size)) {
                $this->output->error('The size must be an integer greater than 0');
                $hasValidInput = false;
            }
        }

        if (!$this->isPositiveInteger($iterations)) {
            $this->output->error('The number of iterations must be an integer greater than 0');
            $hasValidInput = false;
        }

        if (!$this->isOptionalInteger($seed)) {
            $this->output->error('The seed must be an integer');
            $hasValidInput = false;
        }

        return $hasValidInput;
    }

    private function isPositiveInteger(mixed $value): bool
    {
        $positiveInteger = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        return $positiveInteger !== false;
    }

    private function isOptionalInteger(mixed $value): bool
    {
        $integer = filter_var($value, FILTER_VALIDATE_INT);

        return $integer !== false || $value === null;
    }
}
