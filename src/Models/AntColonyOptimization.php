<?php

namespace Aco\Models;

use Aco\Models\Node;
use Aco\Models\Path;
use Exception;

abstract class AntColonyOptimization
{
    /**
     * Constants to error messages.
     */
    const INVALID_NODES = 'This node list is not valid.';
    const ERROR_TO_FIND_NEXT_NODE = 'Error: cannot find next node to visit.';

    /** 
     * All Nodes.
     * 
     * A Node is a partial solution.
     * 
     * This is a mapped variable (["id" => Node])
     */
    private array $nodes;

    /**
     * Pheromone.
     */
    private Pheromone $pheromone;

    /**
     * All paths.
     * 
     * A path is identified by 2 Nodes
     * 
     * Array of Path
     */
    private array $paths;

    /**
     * Total number of ants.
     */
    private int $totalAnts;

    public function __construct(
        array $nodes,
        Pheromone $pheromone,
        int $totalAnts,
    ) {
        foreach ($nodes as $node) {
            if (gettype($node) !== Node::class) {
                throw new Exception(self::INVALID_NODES);
            }
        }

        $this->nodes = $this->mapNodes($nodes);

        $this->paths = $this->makePaths();

        $this->pheromone = $pheromone;
        $this->totalAnts = $totalAnts;
    }

    /**
     * Executes the Ant Colony Optimization
     */
    public function run(int $initialPosition = -1)
    {
        $bestSolution = [];
        $bestSolutionValue = 0;

        for ($i = 0; $i < $this->totalAnts; $i++) {
            $solution = $this->releaseAnt($initialPosition);
            $solutionValue = $solution->calculateObjective();

            if ($solution->calculateObjective > $bestSolutionValue) {
                $bestSolution = $solution;
                $bestSolutionValue = $solutionValue;
            }

            $this->updatePheromone($solution->getNodes(), $solutionValue);
        }

        return $bestSolution;
    }


    private function updatePheromone(array $solution, float $solutionValue)
    {
        for ($i = 0; $i < sizeOf($solution) - 1; $i++) {
            $initialNode = $solution[$i];
            $finalNode = $solution[$i + 1];

            foreach ($this->paths as $path) {
                if ($path->isCurrentPath($initialNode, $finalNode)) {
                    $path->increasePheromone($solutionValue);
                }
            }
        }

        foreach ($this->paths as $path) {
            $path->evapore();
        }
    }

    /**
     * Build all pairs nodes paths.
     */
    private function makePaths(): array
    {
        $paths = [];

        foreach ($this->nodes as $node) {
            $adjList = $node->getAdjList();

            foreach ($adjList as $adjNode) {
                $path = new Path($node->getId(), $adjNode, $this->pheromone);

                array_push($paths, $path);
            }
        }

        return $paths;
    }

    /**
     * Release the ant into paths to get a solution
     */
    private function releaseAnt(int $actualPosition = -1): Solution
    {
        $solution = [];
        $tempSolution = [];
        $visited = [];

        do {
            $solution = $tempSolution;
            $visited[] = $actualPosition;
            $nextNodeToVisit = $this->getNextNode($actualPosition, $visited);

            if (!$nextNodeToVisit) {
                break;
            }

            array_push($tempSolution, $nextNodeToVisit);
            $actualPosition = $nextNodeToVisit->getId();
        } while ($this->verifyStopCondition($tempSolution));

        return new Solution($solution);
    }

    /**
     * Map nodes ["nodeId" => Node]
     */
    private function mapNodes(array $nodes): array
    {
        $mappedNodes = [];

        foreach ($nodes as $node) {
            $mappedNodes[$node->getId()] = $node;
        }

        return $mappedNodes;
    }

    /**
     * The ant goes to next node.
     * 
     * @throw Exception
     */
    private function getNextNode(int $actualNodeId, array $visited): null|Node
    {
        if ($actualNodeId === -1) {
            $firstNodeId = array_rand($this->nodes, 1);
            array_push($visited, $firstNodeId);

            return $this->nodes[$firstNodeId];
        }

        $actualNode = $this->nodes[$actualNodeId];
        $adjNodes = $actualNode->getAdjList();
        $notVisitedNodes = array_intersect($adjNodes, $visited);

        if (!$notVisitedNodes) {
            return null;
        }

        /** NodeId => pheromone (to path actualNode >> notVisitedNode) */
        $mappedPheromones = [];
        foreach ($notVisitedNodes as $notVisitedNode) {
            $path = $this->findPath($actualNodeId, $notVisitedNode);
            if ($path) {
                $mappedPheromones[$notVisitedNode] = $path->getPheromone();
            }
        }

        return $this->findNextNodeFollowingPheromone($mappedPheromones);
    }

    /**
     * Find the Path between two nodes.
     */
    private function findPath(int $initialNode, int $finalNode): Path
    {
        foreach ($this->paths as $path) {
            $pathSearched = $path->isCurrentPath($initialNode, $finalNode);

            if ($pathSearched) {
                return $path;
            }
        }

        return false;
    }

    /**
     * Given an array of pheromones, find randomically a next node to visit.
     */
    private function findNextNodeFollowingPheromone(array $mappedPheromones): false|Node
    {
        $potentialNodesTotalPheromone = 0;
        foreach ($mappedPheromones as $mappedPheromone) {
            $potentialNodesTotalPheromone += $mappedPheromone;
        }

        /** Use a random value to chose next node.
         * 
         * The most pheromone a path to node have, bigger the chance of the node been chosen.
         */
        $randValue = rand(0, ceil($potentialNodesTotalPheromone));

        foreach ($mappedPheromones as $nodeId => $mappedPheromone) {
            $randValue -= $mappedPheromone;
            if ($randValue <= 0) {
                return $this->nodes[$nodeId];
            }
        }

        throw new Exception(self::ERROR_TO_FIND_NEXT_NODE);
    }

    /**
     * Function to verify the stop condition when building a solution.
     */
    abstract protected function verifyStopCondition(array $solution): bool;
}
