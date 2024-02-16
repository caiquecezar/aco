<?php

namespace Aco\Utils\Traits;

trait AdjListBuilder
{
    /**
     * Builds a default Adjacent List for nodes, representing all pairs of paths.
     * 
     * This method iterates over the given array of nodes and assigns to each node 
     * a list of IDs of other nodes that are adjacent to it, excluding itself.
     * 
     * @param array $nodes An array containing the nodes for which the Adjacent List will be built.
     * @return array The array of nodes with their respective Adjacent Lists.
     */
    public function buildAdjList(array $nodes): array
    {
        foreach ($nodes as &$currentNode) {
            $adjNodesIds = [];

            foreach ($nodes as $adjNode) {
                if ($currentNode->getId() == $adjNode->getId()) continue;

                $adjNodesIds[] = $adjNode->getId();
            }

            $currentNode->setAdjList($adjNodesIds);
        }

        return $nodes;
    }
}
