<?php

namespace Aco\Models\Components\Builders;

use Aco\Exceptions\ContextNodesNotFoundException;
use Aco\Exceptions\ContextPathsNotFoundException;
use Aco\Models\Components\Context;
use Aco\Models\Components\EdgeCollection;
use Aco\Models\Components\NodeCollection;
use Aco\Models\Node;
use Aco\Models\Path;
use Aco\Models\Pheromone;
use Aco\Utils\Traits\CheckNodes;
use Exception;

class ContextBuilder
{
    use CheckNodes;

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

    public function addNodesFromArray(array $nodes, bool $auto = true): ContextBuilder
    {
        $this->checkNodes($nodes);
    
        if ($auto) {
            $nodes = $this->buildAdjList($nodes);
        }

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
        if (!$this->nodes) {
            throw new ContextNodesNotFoundException();
        }

        if (!$this->edges) {
            throw new ContextPathsNotFoundException();
        }
        
        return new Context($this->nodes, $this->edges);
    }

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