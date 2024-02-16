<?php

namespace Aco\Models;

/**
 * Class Path represents the path between two adjacents nodes.
 */
class Path
{
    private int $initialNode;
    private int $finalNode;
    private int $currentPheromone;
    private Pheromone $pheromone;

    public function __construct(int $initialNode, int $finalNode, Pheromone $pheromone)
    {
        $this->initialNode = $initialNode;
        $this->finalNode = $finalNode;
        $this->pheromone = $pheromone;
        $this->currentPheromone = $this->pheromone->getInitialPheromone();
    }

    public function evapore(): void
    {
        $this->currentPheromone = (int) $this->currentPheromone - ($this->currentPheromone * $this->pheromone->getEvaporationFee());
    }

    /**
     * Checks if the current path is built from the specified initial and final nodes.
     * 
     * @param int $initialNode The ID of the initial node of the path.
     * @param int $finalNode The ID of the final node of the path.
     * @return bool Returns true if the current path matches the specified initial and final nodes, false otherwise.
     */
    public function isCurrentPath(int $initalNode, int $finalNode): bool
    {
        return $this->initialNode === $initalNode && $this->finalNode === $finalNode;
    }

    public function increasePheromone(float $solutionValue)
    {
        $this->currentPheromone += $this->pheromone->calculatePheromoneIncreaseValue($solutionValue);
    }

    public function getPheromone()
    {
        return $this->currentPheromone;
    }
}
