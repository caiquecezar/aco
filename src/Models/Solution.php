<?php

namespace Aco\Models;

abstract class Solution
{
    /**
     * Array of Aco\Models\Nodes that build a valid solution. 
     */
    protected array $nodes;

    public function __construct(array $nodes = [])
    {
        $this->nodes = $nodes;
    }

    /**
     * Returns all solutions Nodes.
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function buildSolution(array $nodes): Solution
    {
        $concreteClass = get_class($this);

        return new $concreteClass($nodes);
    }

    /**
     * Function to calculate the objective based on current Solution.
     */
    public abstract function calculateObjective(): float;
}
