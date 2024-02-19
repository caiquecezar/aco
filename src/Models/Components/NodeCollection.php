<?php

namespace Aco\Models\Components;

use Aco\Models\Node;

class NodeCollection
{
    private array $nodes;

    public function __construct(array $nodes)
    {
        foreach ($nodes as $node) {
            //TODO: não é node exception
            $this->addNode($node);
        }
    }

    public function addNode(Node $node): void
    {
        $this->nodes[$node->getId()] = $node;
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function getNodeById(int $id): Node
    {
        return $this->nodes[$id];
    }

    public function getNotVisitedFrom(int $currentNodeId, array $visitedNodesIds): array
    {
        $actualNode = $this->nodes[$currentNodeId];
        $adjNodes = $actualNode->getAdjList();
        return array_diff($adjNodes, $visitedNodesIds);
    }
}
