<?php

namespace Aco\Models\Components;

use Aco\Models\Node;

class Context
{
    private NodeCollection $nodes;
    private EdgeCollection $edges;

    /**
     * Context constructor.
     *
     * @param NodeCollection $nodes Collection of nodes in the context.
     * @param EdgeCollection $edges Collection of edges in the context.
     */
    public function __construct(NodeCollection $nodes, EdgeCollection $edges)
    {
        $this->nodes = $nodes;
        $this->edges = $edges;
    }

    /**
     * Retrieves the next node to be visited by the ant.
     *
     * @param int $currentNodeId The ID of the current node.
     * @param array $visited An array containing IDs of nodes already visited.
     * @return Node|null The next node to be visited, or null if no more nodes are available.
     */
    public function getNextNode(int $currentNodeId, array $visited): null|Node
    {
        if ($currentNodeId === -1) {
            $firstNodeId = array_rand($this->nodes->getNodes(), 1);

            return $this->nodes->getNodeById($firstNodeId);
        }

        $notVisitedNodes = $this->nodes->getNotVisitedFrom($currentNodeId, $visited);

        if (!$notVisitedNodes) {
            return null;
        }

        $nodeId = $this->edges->findNextNodeFollowingPheromone($currentNodeId, $notVisitedNodes);

        return $this->nodes->getNodeById($nodeId);
    }

    /**
     * Update the pheromone levels of the paths based on the given solution.
     *
     * @param array $solution An array representing the solution found by the ant.
     * @param float $solutionValue The value of the solution.
     * @return void
     */
    public function updatePathsPheromone(array $solution, float $solutionValue): void
    {
        $this->edges->updatePheromone($solution, $solutionValue);
    }
}
