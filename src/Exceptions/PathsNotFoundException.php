<?php

namespace Aco\Exceptions;

use Exception;

class PathsNotFoundException extends Exception
{
    const PATHS_NOT_FOUND = 'Paths not found.';

    public function __construct()
    {
        parent::__construct(self::PATHS_NOT_FOUND);
    }
}
