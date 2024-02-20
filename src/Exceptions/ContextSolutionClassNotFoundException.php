<?php

namespace Aco\Exceptions;

use Exception;

class ContextSolutionClassNotFoundException extends Exception
{
    const SOLUTION_NOT_FOUND = 'Context solution class not found.';

    public function __construct()
    {
        parent::__construct(self::SOLUTION_NOT_FOUND);
    }
}
