<?php

namespace Tests\Utils\Concretes\Default;

use CaiqueCezar\Aco\Components\Abstracts\Pheromone;

class PheromoneImpl extends Pheromone
{
    public function calculatePheromoneIncreaseValue(float $objectiveReached): int
    {
        return $objectiveReached;
    }
}