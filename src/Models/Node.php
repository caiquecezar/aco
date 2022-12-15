<?php

namespace Aco\Models;

use Aco\Helpers\AutoIncrement;

abstract class Node
{
    /**
     * Node id.
     */
    private int $id;

    /**
     * Array of adjacent nodes ids.
     */
    private array $adjList;

    public function __construct(array $adjList)
    {
        $this->setId();
        $this->visited = [];
        $this->adjList = $adjList;
    }

    /**
     * List of nodes that can be visited after actual node
     * 
     * Array of Integer (NodesIds)
     */
    public function getAdjList(): array
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
