<?php

namespace Aco\Exceptions;

use Exception;

class NextNodeNotFoundException extends Exception
{
    const ERROR_TO_FIND_NEXT_NODE = 'Cannot find next node to visit.';

    public function __construct()
    {
        parent::__construct(self::ERROR_TO_FIND_NEXT_NODE);
    }
}
