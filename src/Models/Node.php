<?php

namespace Aco\Models;

use Aco\Helpers\Concrete\AutoIncrement;

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

    public function setAdjList(array $adjList): void
    {
        $this->adjList = $adjList;
    }

    public function getId(): int
    {
        return $this->id;
    }

    private function setId(): void
    {
        $autoIncrement = AutoIncrement::getInstance();

        $this->id = $autoIncrement->nextId();
    }
}
