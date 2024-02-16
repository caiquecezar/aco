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

    /**
     * Builds a new solution instance based on the provided array of nodes.
     * 
     * This method creates a new solution instance of the same concrete class as the current one,
     * using the provided array of nodes. It allows for the creation of alternative solutions 
     * based on different sets of nodes.
     * 
     * @param array $nodes An array of nodes to construct the new solution.
     * 
     * @return Solution A new solution instance created with the provided array of nodes.
     */
    public function buildSolution(array $nodes): Solution
    {
        $concreteClass = get_class($this);

        return new $concreteClass($nodes);
    }

    /**
     * Calculates the objective based on the current solution.
     * 
     * This is an abstract function, and its implementation is specific to each problem.
     * 
     * @return float The objective value calculated based on the current solution.
     */
    public abstract function calculateObjective(): float;
}
