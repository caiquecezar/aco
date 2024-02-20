<?php

namespace Aco\Utils\Traits;

use Aco\Components\Abstracts\Node;
use Aco\Exceptions\VariableIsNotANodeException;

trait CheckNodes
{
    /**
     * Checks if the elements in the given array are instances of Node.
     * 
     * @param array $nodes An array of elements to check.
     * @throws VariableIsNotANodeException If any element in the array is not an instance of Node.
     * @return void
     */
    private function checkNodes(array $nodes): void
    {
        foreach ($nodes as $node) {
            if (!($node instanceof Node)) {
                throw new VariableIsNotANodeException();
            }
        }
    }
}
