<?php

namespace Aco\Components\Abstracts;

/**
 * This is an abstract class representing pheromone in the Ant Colony Optimization algorithm.
 * It has methods that are specific to each problem.
 * Calibrate the abstract method to get better solutions.
 */
abstract class Pheromone
{
    protected int $initialPheromone;
    protected float $evaporationFee;

    /**
     * Pheromone constructor.
     *
     * @param int $initialPheromone The initial pheromone level.
     * @param float $evaporationFee The rate of evaporation for the pheromone.
     */
    public function __construct(int $initialPheromone, float $evaporationFee)
    {
        $this->initialPheromone = $initialPheromone;
        $this->evaporationFee = $evaporationFee;
    }

    /**
     * Retrieves the initial pheromone level.
     *
     * @return int The initial pheromone level.
     */
    public function getInitialPheromone(): int
    {
        return $this->initialPheromone;
    }

    /**
     * Retrieves the evaporation fee for the pheromone.
     *
     * @return float The evaporation fee.
     */
    public function getEvaporationFee(): float
    {
        return $this->evaporationFee;
    }

    /**
     * Calculates the increase in pheromone level based on the objective reached.
     * 
     * This is an abstract function; its implementation is specific to each problem.
     *
     * @param float $objectiveReached The objective reached, used to calculate the increase in pheromone.
     * @return int The calculated increase in pheromone level.
     */
    abstract public function calculatePheromoneIncreaseValue(float $objectiveReached): int;
}
