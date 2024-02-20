<?php

namespace Aco\Utils\Traits;

use Aco\Exceptions\VariableIsNotANodeException;
use Aco\Models\Node;
use Exception;

trait CheckNodes
{
    private function checkNodes(array $nodes): void
    {
        foreach ($nodes as $node) {
            if (!($node instanceof Node)) {
                throw new VariableIsNotANodeException();
            }
        }
    }
}
