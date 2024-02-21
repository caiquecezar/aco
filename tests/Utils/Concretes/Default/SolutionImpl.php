<?php

namespace Tests\Utils\Concretes\Default;

use CaiqueCezar\Aco\Components\Abstracts\Solution;

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
