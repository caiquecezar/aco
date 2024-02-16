<?php

namespace Aco\Models;

use Aco\Exceptions\InvalidNodesException;
use Aco\Exceptions\NextNodeNotFoundException;
use Aco\Exceptions\PathsNotFoundException;
use Aco\Exceptions\SolutionNotFoundException;
use Aco\Models\Node;
use Aco\Models\Path;
use Aco\Helpers\Traits\AdjListBuilder;

/**
 * This is an abstract class. 
 * It has methods that are specific for each problem.
 */
abstract class AntColonyOptimization
{
    use AdjListBuilder;

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

    /**
     * Solution concrete class to be used in algorithm
     */
    private Solution $solution;

    /**
     * Constructor for the AntColonyOptimization class.
     *
     * Initializes an instance of the AntColonyOptimization class with the provided parameters.
     *
     * @param array     $nodes         An array of Node objects representing the nodes in the optimization problem.
     * @param Pheromone $pheromone     The Pheromone object representing the pheromone information in the optimization.
     * @param int       $totalAnts     The total number of ants to be used in the optimization process.
     * @param Solution  $solution      An empty instance of the Solution class representing the concrete solution for the problem.
     * @param bool      $buildAdjList  (Optional) Indicates whether to build the adjacency list for nodes. Default is true.
     *                                 If you chose not create paths automatically, you should assure that nodes has adjacency list
     *                                 and make the paths before run the Ant Colony Optimization algorithm.
     *
     * @throws InvalidNodesException  Thrown if any of the provided nodes is not a subclass of Node.
     */
    public function __construct(
        array $nodes,
        Pheromone $pheromone,
        int $totalAnts,
        Solution $solution,
        bool $buildAdjList = true
    ) {
        foreach ($nodes as $node) {
            if (!is_subclass_of($node, Node::class)) {
                throw new InvalidNodesException();
            }
        }

        $this->pheromone = $pheromone;
        $this->totalAnts = $totalAnts;
        $this->solution = $solution;

        $this->nodes = $this->mapNodes($nodes);
        if ($buildAdjList) {
            $this->nodes = $this->buildAdjList($this->nodes);
            $this->makePaths();
        }
    }

    /**
     * Executes the Ant Colony Optimization algorithm.
     * 
     * @param int $initialPosition The initial position for the ants. Default is -1.
     * @return Solution The best solution found by the algorithm.
     * @throws PathsNotFoundException If unable to find the paths between the nodes.
     * @throws SolutionNotFoundException If unable to find a solution.
     */
    public function run(int $initialPosition = -1): Solution
    {
        $bestSolution = [];
        $bestSolutionValue = 0;

        if (empty($this->paths)) {
            throw new PathsNotFoundException();
        }

        for ($i = 0; $i < $this->totalAnts; $i++) {
            $solution = $this->releaseAnt($initialPosition);
            $solutionValue = $solution->calculateObjective();

            if ($solutionValue >= $bestSolutionValue) {
                $bestSolution = $solution;
                $bestSolutionValue = $solutionValue;
            }

            $this->updatePheromone($solution->getNodes(), $solutionValue);
        }

        if (!$bestSolution) {
            throw new SolutionNotFoundException();
        }

        return $bestSolution;
    }

    /**
     * Constructs all pairs of paths between nodes.
     * 
     * @return void
     */
    public function makePaths(): void
    {
        $paths = [];

        foreach ($this->nodes as $node) {
            /** @var Node $node */
            $adjList = $node->getAdjList();

            foreach ($adjList as $adjNode) {
                $path = new Path($node->getId(), $adjNode, $this->pheromone);

                $paths[] = $path;
            }
        }

        $this->paths = $paths;
    }

    /**
     * Updates the pheromone levels along the paths according to the given solution value.
     * 
     * @param array $solution The solution path.
     * @param float $solutionValue The value of the solution.
     * @return void
     */
    private function updatePheromone(array $solution, float $solutionValue): void
    {
        for ($i = 0; $i < sizeOf($solution) - 1; $i++) {
            /** @var Node $initialNode */
            $initialNode = $solution[$i];
            /** @var Node $finalNode */
            $finalNode = $solution[$i + 1];

            foreach ($this->paths as $path) {
                /** @var Path $path */
                if ($path->isCurrentPath($initialNode->getId(), $finalNode->getId())) {
                    $path->increasePheromone($solutionValue);
                }
            }
        }

        foreach ($this->paths as $path) {
            /** @var Path $path */
            $path->evapore();
        }
    }

    /**
     * Releases an ant into paths to explore and find a solution.
     * 
     * @param int $actualPosition The starting position of the ant. Default is -1.
     * @return Solution The solution obtained by the ant after exploring paths.
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

            $tempSolution[] = $nextNodeToVisit;
            $actualPosition = $nextNodeToVisit->getId();
        } while ($this->verifyStopCondition($tempSolution));

        return $this->solution->buildSolution($solution);
    }

    /**
     * Maps an array of nodes to an associative array where keys are node IDs and values are Node objects.
     *
     * @param array $nodes An array containing Node objects to be mapped.
     * @return array An associative array mapping node IDs to Node objects.
     */
    private function mapNodes(array $nodes): array
    {
        $mappedNodes = [];

        foreach ($nodes as $node) {
            /** @var Node $node */
            $mappedNodes[$node->getId()] = $node;
        }

        return $mappedNodes;
    }

    /**
     * Retrieves the next node to be visited by the ant.
     *
     * @param int $actualNodeId The ID of the current node.
     * @param array $visited An array containing IDs of nodes already visited.
     * @return Node|null The next node to be visited, or null if no more nodes are available.
     */
    private function getNextNode(int $actualNodeId, array $visited): null|Node
    {
        if ($actualNodeId === -1) {
            $firstNodeId = array_rand($this->nodes, 1);
            $visited[] = $firstNodeId;

            return $this->nodes[$firstNodeId];
        }

        $actualNode = $this->nodes[$actualNodeId];
        $adjNodes = $actualNode->getAdjList();
        $notVisitedNodes = array_diff($adjNodes, $visited);

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
     * Find the Path object representing the path between two nodes.
     *
     * @param int $initialNode The ID of the initial node.
     * @param int $finalNode The ID of the final node.
     * @return Path|false The Path object if found, false otherwise.
     */
    private function findPath(int $initialNode, int $finalNode): Path
    {
        foreach ($this->paths as $path) {
            /** @var Path $path*/
            $pathSearched = $path->isCurrentPath($initialNode, $finalNode);

            if ($pathSearched) {
                return $path;
            }
        }

        return false;
    }

    /**
     * Given an array of pheromones, find randomly a next node to visit.
     * 
     * The method may throw a NextNodeNotFoundException if it fails to find a suitable node.
     * 
     * @param array $mappedPheromones The mapped pheromones.
     * @return Node|false The next node to visit, or false if no node is found.
     * @throws NextNodeNotFoundException If no suitable node is found.
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

        throw new NextNodeNotFoundException();
    }

    /**
     * Function to verify the stop condition when building a solution.
     * 
     * This is an abstract function, its implementation is specific for each problem
     *
     * @param array $solution The current solution being evaluated.
     * @return bool Whether the stop condition is met or not.
     */
    abstract protected function verifyStopCondition(array $solution): bool;
}
