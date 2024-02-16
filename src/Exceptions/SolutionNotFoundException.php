<?php

namespace Aco\Exceptions;

use Exception;

class SolutionNotFoundException extends Exception
{
    const UNABLE_TO_FIND_SOLUTION = 'Unable to find a solution.';

    public function __construct()
    {
        parent::__construct(self::UNABLE_TO_FIND_SOLUTION);
    }
}
