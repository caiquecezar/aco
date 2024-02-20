<?php

namespace Aco\Exceptions;

use Exception;

class VariableIsNotANodeException extends Exception
{
    const INCORRECT_INSTANCE = 'Variable is not a \Aco\Models\Node instance.';

    public function __construct()
    {
        parent::__construct(self::INCORRECT_INSTANCE);
    }
}
