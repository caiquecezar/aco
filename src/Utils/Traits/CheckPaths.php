<?php

namespace Aco\Utils\Traits;

use Aco\Components\Path;
use Aco\Exceptions\VariableIsNotAPathException;

trait CheckPaths
{
    /**
     * Checks if the elements in the given array are instances of Path.
     * 
     * @param array $paths An array of elements to check.
     * @throws VariableIsNotAPathException If any element in the array is not an instance of Path.
     * @return void
     */
    private function checkPaths(array $paths): void
    {
        foreach ($paths as $path) {
            if (!($path instanceof Path)) {
                throw new VariableIsNotAPathException();
            }
        }
    }
}
