<?php

namespace Aco\Components\Builders;

use Aco\Components\Abstracts\Pheromone;
use Aco\Components\Collections\EdgeCollection;
use Aco\Components\Collections\NodeCollection;
use Aco\Components\Context;
use Aco\Components\Path;
use Aco\Exceptions\ContextNodesNotFoundException;
use Aco\Exceptions\ContextPathsNotFoundException;
use Aco\Exceptions\ContextSolutionClassNotFoundException;
use Aco\Utils\Traits\CheckNodes;

class ContextBuilder
{
    use CheckNodes;

    private ?NodeCollection $nodes = null;
    private ?EdgeCollection $edges = null;
    private ?string $solutionClass = null;

    /**
     * Creates a new instance of ContextBuilder.
     *
     * @return ContextBuilder A new instance of ContextBuilder.
     */
    public static function builder(): ContextBuilder
    {
        return new ContextBuilder();
    }

    /**
     * Adds a NodeCollection to the builder.
     *
     * @param NodeCollection $nodes The NodeCollection to add.
     * @return ContextBuilder The updated ContextBuilder.
     */
    public function addNodes(NodeCollection $nodes): ContextBuilder
    {
        $this->nodes = $nodes;

        return $this;
    }

    /**
     * Adds nodes from an array to the builder.
     *
     * @param array $nodes An array of nodes to add.
     * @param bool $auto Indicates whether to automatically build adjacency lists for the nodes. Defaults to true.
     * @return ContextBuilder The updated ContextBuilder.
     */
    public function addNodesFromArray(array $nodes, bool $auto = true): ContextBuilder
    {
        $this->checkNodes($nodes);

        if ($auto) {
            $nodes = $this->buildAdjList($nodes);
        }

        $this->nodes = new NodeCollection($nodes);

        return $this;
    }

    /**
     * Creates paths between nodes using the provided pheromone.
     *
     * @param Pheromone $pheromone The pheromone to use for creating paths.
     * @return ContextBuilder The updated ContextBuilder.
     */
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

    /**
     * Adds an EdgeCollection to the builder.
     *
     * @param EdgeCollection $edges The EdgeCollection to add.
     * @return ContextBuilder The updated ContextBuilder.
     */
    public function addPaths(EdgeCollection $edges): ContextBuilder
    {
        $this->edges = $edges;

        return $this;
    }

    /**
     * Adds paths from an array to the builder.
     *
     * @param array $paths An array of paths to add.
     * @return ContextBuilder The updated ContextBuilder.
     */
    public function addPathsFromArray(array $paths): ContextBuilder
    {
        $this->edges = new EdgeCollection($paths);

        return $this;
    }

    /**
     * Builds the Context object using the provided nodes and edges.
     *
     * @return Context The built Context object.
     * @throws ContextNodesNotFoundException If nodes are not provided.
     * @throws ContextPathsNotFoundException If paths are not provided.
     */
    public function build(): Context
    {
        if (!$this->nodes) {
            throw new ContextNodesNotFoundException();
        }

        if (!$this->edges) {
            throw new ContextPathsNotFoundException();
        }

        if (!$this->solutionClass) {
            throw new ContextSolutionClassNotFoundException();
        }

        return new Context($this->nodes, $this->edges, $this->solutionClass);
    }

    /**
     * Adds a concrete Solution class string.
     *
     * @param string $solutionClass An solution class.
     * @return ContextBuilder The updated ContextBuilder.
     */
    public function addSolution(string $solutionClass): ContextBuilder
    {
        $this->solutionClass = $solutionClass;

        return $this;
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
