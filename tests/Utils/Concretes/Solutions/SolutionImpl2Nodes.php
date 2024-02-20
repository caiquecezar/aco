<?php

namespace Tests\Utils\Concretes\Solutions;

use Aco\Components\Abstracts\Solution;

class SolutionImpl2Nodes extends Solution
{
    public function calculateObjective(): float
    {
        return 0;
    }

    public function isValidSolution(): bool
    {
        return count($this->nodes) == 2;
    }
}
