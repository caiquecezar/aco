<?php

namespace Aco\Models\Components;

use Aco\Models\Node;
use Aco\Models\Path;
use Aco\Models\Pheromone;
use Exception;

class ContextBuilder
{
    private NodeCollection $nodes;
    private EdgeCollection $edges;

    public static function builder(): ContextBuilder
    {
        return new ContextBuilder();
    }

    public function addNodes(NodeCollection $nodes): ContextBuilder
    {
        $this->nodes = $nodes;

        return $this;
    }

    public function addNodesFromArray(array $nodes): ContextBuilder
    {
        $this->nodes = new NodeCollection($nodes);

        return $this;
    }

    public function createPaths(Pheromone $pheromone): ContextBuilder
    {
        $paths = [];

        foreach ($this->nodes->getNodes() as $node) {
            /** @var Node $node */
            $adjList = $node->getAdjList();
            $currentNodeId = $node->getId();
            foreach ($adjList as $adjNodeId) {
                $path = new Path($currentNodeId, $adjNodeId, $pheromone);
                $reversePath = new Path($adjNodeId, $currentNodeId, $pheromone);
                if (!in_array($reversePath, $paths)) {
                    $paths[] = $path;
                }
            }
        }

        $this->edges = new EdgeCollection($paths);

        return $this;
    }

    public function addPaths(EdgeCollection $edges): ContextBuilder
    {
        $this->edges = $edges;

        return $this;
    }

    public function addPathsFromArray(array $paths): ContextBuilder
    {
        $this->edges = new EdgeCollection($paths);

        return $this;
    }

    public function build(): Context
    {
        if (!$this->nodes || $this->edges) {
            throw new Exception("Precisa dos nos e arestas");
        }
        return new Context($this->nodes, $this->edges);
    }
}