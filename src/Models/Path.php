<?php

namespace Aco\Models;

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
     * Verify if the current path is build from $initialNode and $finalNode
     */
    public function isCurrentPath(int $initalNode, int $finalNode): bool
    {
        return $this->initialNode === $initalNode && $this->finalNode === $finalNode;
    }

    public function increasePheromone(int $solutionValue)
    {
        $this->currentPheromone += $this->pheromone->calculatePheromoneIncreaseValue($solutionValue);
    }

    public function getPheromone()
    {
        return $this->currentPheromone;
    }
}
