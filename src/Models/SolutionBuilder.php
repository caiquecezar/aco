<?php

namespace Aco\Models;

abstract class SolutionBuilder
{
    private array $nodes;

    public function addSolution(array $nodes)
    {
        $this->nodes = $nodes;

        return $this;
    }

    protected abstract function calculateObjective(): int;
}
