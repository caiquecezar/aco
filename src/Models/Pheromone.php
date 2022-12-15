<?php

namespace Aco\Models;

abstract class Pheromone
{
    private int $initialPheromone;
    private float $evaporationFee;

    public function __construct(int $initialPheromone, float $evaporationFee)
    {
        $this->initialPheromone = $initialPheromone;
        $this->evaporationFee = $evaporationFee;
    }

    public function getInitialPheromone(): int
    {
        return $this->initialPheromone;
    }

    public function getEvaporationFee(): float
    {
        return $this->evaporationFee;
    }

    abstract public function calculatePheromoneIncreaseValue(float $objectiveReached): float;
}
