<?php

namespace Aco\Exceptions;

use Exception;

class ClassIsNotASolutionInstanceException extends Exception
{
    const INCORRECT_INSTANCE = 'Class is not a \Aco\Models\Solution instance.';

    public function __construct()
    {
        parent::__construct(self::INCORRECT_INSTANCE);
    }
}
