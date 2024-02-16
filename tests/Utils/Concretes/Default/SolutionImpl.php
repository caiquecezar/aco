<?php

namespace Tests\Utils\Concretes\Default;

use Aco\Models\Solution;

class SolutionImpl extends Solution
{
    public function calculateObjective(): float
    {
        return 0;
    }
}
