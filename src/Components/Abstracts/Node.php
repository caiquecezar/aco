<?php

namespace CaiqueCezar\Aco\Components\Abstracts;

use CaiqueCezar\Aco\Utils\AutoIncrement;

/**
 * This is an abstract class representing a node in a graph.
 * It doesn't have any abstract method but should be adapted for your problem context.
 */
abstract class Node
{
    private int $id;
    private array $adjList = [];

    /**
     * Constructor for the Node class.
     */
    public function __construct()
    {
        $autoIncrement = AutoIncrement::getInstance();

        $this->id = $autoIncrement->nextId();
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

    /**
     * Sets the list of adjacent nodes.
     * 
     * @param array $adjList An array containing the IDs of adjacent nodes.
     * @return void
     */
    public function setAdjList(array $adjList): void
    {
        $this->adjList = $adjList;
    }

    /**
     * Retrieves the ID of the node.
     * 
     * @return int The ID of the node.
     */
    public function getId(): int
    {
        return $this->id;
    }
}
