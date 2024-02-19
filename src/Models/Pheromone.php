<?php

namespace Aco\Models;

/**
 * This is an abstract class. 
 * It has methods that are specific for each problem.
 * Calibrate the abstract method to get better solutions.
 */
abstract class Pheromone
{
    protected int $initialPheromone;
    protected float $evaporationFee;

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

    /**
     * Calculates the increase pheromone according with $objectiveReached.
     * 
     * This is an abstract function, its implementation is specific for each problem
     */
    abstract public function calculatePheromoneIncreaseValue(float $objectiveReached): int;
}
