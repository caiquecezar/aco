<?php

namespace Aco\Models;

/**
 * Class Path represents the path between two adjacent nodes.
 */
class Path
{
    private int $initialNode;
    private int $finalNode;
    private int $currentPheromone;
    private Pheromone $pheromone;

    /**
     * Path constructor.
     *
     * @param int $initialNode The ID of the initial node of the path.
     * @param int $finalNode The ID of the final node of the path.
     * @param Pheromone $pheromone The pheromone object associated with the path.
     */
    public function __construct(int $initialNode, int $finalNode, Pheromone $pheromone)
    {
        $this->initialNode = $initialNode;
        $this->finalNode = $finalNode;
        $this->pheromone = $pheromone;
        $this->currentPheromone = $this->pheromone->getInitialPheromone();
    }

    /**
     * Evaporates the pheromone along the path.
     * 
     * @return void
     */
    public function evapore(): void
    {
        $decrease = (int) floor($this->currentPheromone * $this->pheromone->getEvaporationFee());

        $this->currentPheromone = max($this->currentPheromone - $decrease, 1);
    }

    /**
     * Checks if the current path is built from the specified initial and final nodes.
     * 
     * @param int $initialNode The ID of the initial node of the path.
     * @param int $finalNode The ID of the final node of the path.
     * @return bool Returns true if the current path matches the specified initial and final nodes, false otherwise.
     */
    public function isCurrentPath(int $initialNode, int $finalNode): bool
    {
        return $this->initialNode === $initialNode && $this->finalNode === $finalNode;
    }

    /**
     * Increases the pheromone level along the path based on the solution value.
     * 
     * @param float $solutionValue The value of the solution.
     * @return void
     */
    public function increasePheromone(float $solutionValue)
    {
        $this->currentPheromone += $this->pheromone->calculatePheromoneIncreaseValue($solutionValue);
    }

    /**
     * Retrieves the current pheromone level along the path.
     * 
     * @return int The current pheromone level.
     */
    public function getPheromone()
    {
        return max($this->currentPheromone, 1);
    }
}
