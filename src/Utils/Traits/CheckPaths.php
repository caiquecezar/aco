<?php

namespace Aco\Utils\Traits;

use Aco\Exceptions\VariableIsNotAPathException;
use Aco\Models\Path;
use Exception;

trait CheckPaths
{
    private function checkPaths(array $paths): void
    {
        foreach ($paths as $path) {
            if (!($path instanceof Path)) {
                throw new VariableIsNotAPathException();
            }
        }
    }
}
