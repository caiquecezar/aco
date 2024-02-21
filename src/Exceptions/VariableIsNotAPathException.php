<?php

namespace Aco\Exceptions;

use Exception;

class VariableIsNotAPathException extends Exception
{
    const INCORRECT_INSTANCE = 'Variable is not a \Aco\Models\Path instance.';

    public function __construct()
    {
        parent::__construct(self::INCORRECT_INSTANCE);
    }
}
