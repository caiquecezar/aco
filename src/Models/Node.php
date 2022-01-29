<?php

namespace Aco\Models;

use Aco\Helpers\AutoIncrement;

abstract class Node
{
    private int $id;
    private array $adjList;

    public function __construct(array $adjList)
    {
        $this->setId();
        $this->visited = [];
    }

    public function getAdjList()
    {
        return $this->adjList;
    }

    public function getId()
    {
        return $this->id;
    }

    private function setId()
    {
        $autoIncrement = AutoIncrement::getInstance();

        $this->id = $autoIncrement->nextId();
    }
}
