<?php

namespace Aco\Components;

use Aco\Components\Abstracts\Solution;
use Aco\Exceptions\SolutionNotFoundException;

class AntColonyOptimization
{
    private Context $context;
    private int $totalAnts;

    /**
     * Constructor for AntColonyOptimization class.
     * 
     * @param Context $context The context for the optimization algorithm.
     * @param int $totalAnts The total number of ants to be used in the algorithm.
     * @param string $solution The solution concrete class to be used in the algorithm.
     */
    public function __construct(
        Context $context,
        int $totalAnts,
    ) {
        $this->context = $context;
        $this->totalAnts = $totalAnts;
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
        $bestSolution = null;
        $bestSolutionValue = null;

        for ($i = 0; $i < $this->totalAnts; $i++) {
            $solution = $this->context->releaseAnt($initialPosition);

            if (!$solution->isValidSolution()) {
                continue;
            }

            $solutionValue = $solution->calculateObjective();

            if (!$bestSolutionValue || $solutionValue >= $bestSolutionValue) {
                $bestSolution = $solution;
                $bestSolutionValue = $solutionValue;
            }

            $this->context->updatePathsPheromone($solution);
        }

        if (!$bestSolution) {
            throw new SolutionNotFoundException();
        }

        return $bestSolution;
    }
}
