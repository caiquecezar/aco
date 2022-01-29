<?php

namespace Aco\Models;

class Path
{
    private int $initialNode;
    private int $finalNode;
    private float $evaporationFee;
    private float $pheromone;

    public function __construct(int $initialNode, int $finalNode, int $initialPheromone, float $evaporationFee)
    {
        $this->initialNode = $initialNode;
        $this->finalNode = $finalNode;
        $this->pheromone = $initialPheromone;
        $this->evaporationFee = $evaporationFee;
    }

    public function evapore(): void
    {
        $this->pheromone = $this->pheromone - ($this->pheromone * $this->evaporationFee);
    }

    public function verifyPath(int $initalNode, int $finalNode): bool
    {
        return $this->initialNode === $initalNode && $this->finalNode === $finalNode;
    }

    public function increasePheromone(int $value)
    {
        $this->pheromone += $value;
    }

    public function getPheromone()
    {
        return $this->pheromone;
    }
}
