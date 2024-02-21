<?php

namespace CaiqueCezar\Aco\Components\Factories;

use CaiqueCezar\Aco\Components\Abstracts\Solution;
use CaiqueCezar\Aco\Exceptions\ClassIsNotASolutionInstanceException;
use CaiqueCezar\Aco\Exceptions\InvalidSolutionTypeException;

class SolutionFactory
{
    /**
     * Create an instance of a concrete solution.
     *
     * @param string $solutionType The type of solution to create.
     * @return Solution An instance of class that extends Solution.
     * @throws InvalidSolutionTypeException If the solution type is not recognized.
     */
    public static function createSolution(string $solutionType): Solution
    {
        $solution = new $solutionType();

        if (!($solution instanceof Solution)) {
            throw new ClassIsNotASolutionInstanceException();
        }

        return $solution;
    }
}
