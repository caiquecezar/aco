<?php

namespace Tests\Utils\Concretes\Default;

use Aco\Models\AntColonyOptimization;

class AntColonyOptimizationImpl extends AntColonyOptimization
{
    public function verifyStopCondition(array $solution): bool {
        return true;
    }
}