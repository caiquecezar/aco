<?php

namespace CaiqueCezar\Aco\Components\Collections;

use CaiqueCezar\Aco\Components\Abstracts\Node;
use CaiqueCezar\Aco\Utils\Traits\CheckNodes;

class NodeCollection
{
    use CheckNodes;

    private array $nodes;

    /**
     * NodeCollection constructor.
     *
     * @param array $nodes An array of nodes to initialize the NodeCollection.
     */
    public function __construct(array $nodes)
    {
        $this->checkNodes($nodes);

        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }

    /**
     * Adds a node to the collection.
     *
     * @param Node $node The node to add.
     * @return void
     */
    public function addNode(Node $node): void
    {
        $this->nodes[$node->getId()] = $node;
    }

    /**
     * Retrieves all nodes in the collection.
     *
     * @return array All nodes in the collection.
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * Retrieves a node by its ID.
     * If id is invalid, return a random Node.
     *
     * @param int $id The ID of the node to retrieve.
     * @return Node The node with the specified ID.
     */
    public function getNodeById(int $id): Node
    {
        if ($id < 0) {
            return $this->nodes[array_rand($this->nodes, 1)];
        }

        return $this->nodes[$id];
    }

    /**
     * Retrieves nodes that have not been visited from the current node.
     *
     * @param int $currentNodeId The ID of the current node.
     * @param array $visitedNodesIds An array of IDs of nodes already visited.
     * @return array An array of IDs representing nodes that have not been visited.
     */
    public function getNotVisitedFrom(int $currentNodeId, array $visitedNodesIds): array
    {
        $actualNode = $this->nodes[$currentNodeId];
        $adjNodes = $actualNode->getAdjList();
        return array_diff($adjNodes, $visitedNodesIds);
    }
}
