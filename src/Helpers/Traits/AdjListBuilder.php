<?php

namespace Aco\Helpers\Traits;

trait AdjListBuilder
{
    /**
     * Build an Adjacent List to Nodes.
     */
    public function buildAdjList(array $nodes): array
    {
        foreach($nodes as &$currentNode) {
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
