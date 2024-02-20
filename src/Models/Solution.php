<?php

namespace Aco\Models;

/**
 * This is an abstract class.
 * It has methods that are specific for each problem.
 * Calibrate the abstract method to get better solutions.
 */
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

    public function addPartialSolution(Node $node): Solution
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * Calculates the objective based on the current solution.
     * 
     * This is an abstract function, and its implementation is specific to each problem.
     * 
     * @return float The objective value calculated based on the current solution.
     */
    public abstract function calculateObjective(): float;

    public abstract function isValidSolution(): bool;
}
