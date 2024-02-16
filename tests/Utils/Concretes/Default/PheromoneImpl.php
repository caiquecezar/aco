<?php

namespace Tests\Utils\Concretes\Default;

use Aco\Models\Pheromone;

class PheromoneImpl extends Pheromone
{
    public function calculatePheromoneIncreaseValue(float $objectiveReached): int
    {
        return $objectiveReached;
    }
}