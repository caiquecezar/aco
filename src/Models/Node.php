<?php

namespace Aco\Models;

use Aco\Utils\AutoIncrement;

/**
 * This is an abstract class. 
 * It doesnt have any abstract method but should be adapted for your problem context.
 */
abstract class Node
{
    /**
     * Node id.
     */
    private int $id;

    /**
     * Array of adjacent nodes ids.
     */
    private array $adjList = [];

    public function __construct()
    {
        $this->setId();
    }

    /**
     * Retrieves the list of nodes that can be visited after the current node.
     * 
     * @return array An array containing the IDs of the adjacent nodes.
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
