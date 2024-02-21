<?php

namespace Tests\Utils\Concretes\Default;

use Aco\Components\Abstracts\Solution;

class SolutionImpl extends Solution
{
    public function calculateObjective(): float
    {
        return 0;
    }

    public function isValidSolution(): bool
    {
        return true;
    }
}
