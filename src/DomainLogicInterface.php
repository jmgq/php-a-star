<?php

namespace JMGQ\AStar;

interface DomainLogicInterface
{
    public function getAdjacentNodes(mixed $node): iterable;

    public function calculateRealCost(mixed $node, mixed $adjacent): float | int;

    public function calculateEstimatedCost(mixed $start, mixed $end): float | int;
}
