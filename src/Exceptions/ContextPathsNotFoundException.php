<?php

namespace Aco\Exceptions;

use Exception;

class ContextPathsNotFoundException extends Exception
{
    const PATHS_NOT_FOUND = 'Context paths not found.';

    public function __construct()
    {
        parent::__construct(self::PATHS_NOT_FOUND);
    }
}
