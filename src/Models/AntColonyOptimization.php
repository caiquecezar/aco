<?php

namespace Aco\Models;

use Aco\Exceptions\SolutionNotFoundException;
use Aco\Models\Components\Context;

/**
 * This is an abstract class. 
 * It has methods that are specific for each problem.
 */
abstract class AntColonyOptimization
{
    private Context $context;

    /**
     * Total number of ants.
     */
    private int $totalAnts;

    /**
     * Solution concrete class to be used in algorithm
     */
    private Solution $solution;

    public function __construct(
        Context $context,
        int $totalAnts,
        Solution $solution,
    ) {
        $this->context = $context;
        $this->totalAnts = $totalAnts;
        $this->solution = $solution;
    }

    /**
     * Executes the Ant Colony Optimization algorithm.
     * 
     * @param int $initialPosition The initial position for the ants. Default is -1.
     * @return Solution The best solution found by the algorithm.
     * @throws SolutionNotFoundException If unable to find a solution.
     */
    public function run(int $initialPosition = -1): Solution
    {
        $bestSolution = [];
        $bestSolutionValue = 0;

        for ($i = 0; $i < $this->totalAnts; $i++) {
            $solution = $this->releaseAnt($initialPosition);
            $solutionValue = $solution->calculateObjective();

            if ($solutionValue >= $bestSolutionValue) {
                $bestSolution = $solution;
                $bestSolutionValue = $solutionValue;
            }

            $this->context->updatePathsPheromone($solution->getNodes(), $solutionValue);
        }

        if (!$bestSolution) {
            throw new SolutionNotFoundException();
        }

        return $bestSolution;
    }


    /**
     * Releases an ant into paths to explore and find a solution.
     * 
     * @param int $actualPosition The starting position of the ant. Default is -1.
     * @return Solution The solution obtained by the ant after exploring paths.
     */
    private function releaseAnt(int $actualPosition = -1): Solution
    {
        $solution = [];
        $tempSolution = [];
        $visited = [];

        do {
            $solution = $tempSolution;
            $nextNodeToVisit = $this->context->getNextNode($actualPosition, $visited);
            
            if (!$nextNodeToVisit) {
                break;
            }
            
            $visited[] = $nextNodeToVisit->getId();
            $tempSolution[] = $nextNodeToVisit;
            $actualPosition = $nextNodeToVisit->getId();
        } while ($this->verifyStopCondition($tempSolution));

        return $this->solution->buildSolution($solution);
    }

    /**
     * Function to verify the stop condition when building a solution.
     * This is an abstract function, its implementation is specific for each problem
     *
     * @param array $solution The current solution being evaluated (an array of Nodes).
     * @return bool Whether the stop condition is met or not.
     */
    abstract protected function verifyStopCondition(array $solution): bool;
}
