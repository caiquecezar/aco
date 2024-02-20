<?php

namespace Aco\Models;

use Aco\Exceptions\SolutionNotFoundException;
use Aco\Models\Components\Factories\SolutionFactory;
use Aco\Models\Components\Context;

class AntColonyOptimization
{
    private Context $context;

    /**
     * Total number of ants.
     */
    private int $totalAnts;

    /**
     * Solution concrete class to be used in algorithm
     */
    private string $solutionClass;

    public function __construct(
        Context $context,
        int $totalAnts,
        string $solution,
    ) {
        $this->context = $context;
        $this->totalAnts = $totalAnts;
        $this->solutionClass = $solution;
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
        $tempSolution = SolutionFactory::createSolution($this->solutionClass);
        $visited = [];

        do {
            $nextNodeToVisit = $this->context->getNextNode($actualPosition, $visited);
            
            if (!$nextNodeToVisit) {
                break;
            }
            
            $tempSolution->addPartialSolution($nextNodeToVisit);

            $visited[] = $nextNodeToVisit->getId();
            $actualPosition = $nextNodeToVisit->getId();
        } while (!$tempSolution->isValidSolution());

        return $tempSolution;
    }
}
