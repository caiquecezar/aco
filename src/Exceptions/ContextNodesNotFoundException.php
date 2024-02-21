<?php

namespace CaiqueCezar\Aco\Exceptions;

use Exception;

class ContextNodesNotFoundException extends Exception
{
    const NODES_NOT_FOUND = 'Context nodes not found.';

    public function __construct()
    {
        parent::__construct(self::NODES_NOT_FOUND);
    }
}
