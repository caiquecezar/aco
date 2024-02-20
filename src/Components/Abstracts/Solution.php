<?php

namespace Aco\Components\Abstracts;

/**
 * This is an abstract class representing a solution in the Ant Colony Optimization algorithm.
 * It has methods that are specific to each problem.
 * Calibrate the abstract method to get better solutions.
 */
abstract class Solution
{
    /**
     * Array of Aco\Models\Nodes that build a valid solution. 
     */
    protected array $nodes;

    /**
     * Solution constructor.
     *
     * @param array $nodes An array of nodes representing the solution. Defaults to an empty array.
     */
    public function __construct(array $nodes = [])
    {
        $this->nodes = $nodes;
    }

    /**
     * Returns all nodes in the solution.
     *
     * @return array An array of nodes representing the solution.
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * Adds a partial solution node to the current solution.
     *
     * @param Node $node The node to add to the solution.
     * @return Solution The updated solution object.
     */
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

    /**
     * Checks if the current solution is valid.
     * 
     * This is an abstract function, and its implementation is specific to each problem.
     * 
     * @return bool Returns true if the solution is valid, false otherwise.
     */
    public abstract function isValidSolution(): bool;
}
