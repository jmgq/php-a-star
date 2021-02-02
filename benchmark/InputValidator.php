<?php

namespace JMGQ\AStar\Benchmark;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

class InputValidator
{
    private $output;

    public function __construct(StyleInterface $output)
    {
        $this->output = $output;
    }

    public function validate(InputInterface $input)
    {
        $hasValidInput = true;

        $sizes = $input->getOption(BenchmarkCommand::SIZE_OPTION);
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

    private function isPositiveInteger($value)
    {
        $positiveInteger = filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

        return $positiveInteger !== false;
    }

    private function isOptionalInteger($value)
    {
        $integer = filter_var($value, FILTER_VALIDATE_INT);

        return $integer !== false || $value === null;
    }
}
