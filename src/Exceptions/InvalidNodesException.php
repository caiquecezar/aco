<?php

namespace Aco\Exceptions;

use Exception;

class InvalidNodesException extends Exception
{
    const INVALID_NODES = 'This node list is not valid.';

    public function __construct()
    {
        parent::__construct(self::INVALID_NODES);
    }
}
