<?php

namespace Aco\Models;

use Aco\Models\Objective;
use Aco\Models\Node;
use Aco\Models\Path;
use Exception;

abstract class AntColonyOptimization
{
    const INVALID_NODES = 'This node list is not valid.';
    const ERROR_TO_FIND_NEXT_NODE = 'Error: cannot find next node to visit.';

    private array $nodes;
    private float $evaporationFee;
    private float $initialPheromone;
    private array $paths;
    private int $totalAnts;

    public function __construct(
        array $nodes,
        float $evaporationFee,
        int $initialPheromone,
        int $totalAnts,
    ) {
        foreach ($nodes as $node) {
            if (gettype($node) !== Node::class) {
                throw new Exception(self::INVALID_NODES);
            }
        }
        $this->nodes = $this->mapNodes($nodes);

        $this->paths = $this->makePaths();

        $this->evaporationFee = $evaporationFee;
        $this->initialPheromone = $initialPheromone;
        $this->totalAnts = $totalAnts;
    }

    public function run(int $initialPosition = -1)
    {
        $bestSolution = [];
        $bestSolutionValue = 0;

        for ($i = 0; $i < $this->totalAnts; $i++) {
            $solution = $this->releaseAnt($initialPosition);
            $solutionValue = $this->calculateSolutionObjectiveValue($solution);

            if ($solutionValue > $bestSolutionValue) {
                $bestSolution = $solution;
                $bestSolutionValue = $solutionValue;
            }

            $solutionPheromoneIncrease = $this->calculatePheromoneIncreaseValue($solutionValue);
            $this->updatePheromone($solution, $solutionPheromoneIncrease);
        }

        return $bestSolution;
    }


    private function updatePheromone(array $solution = [], int $solutionPheromoneIncrease = 0)
    {
        for ($i = 0; $i < sizeOf($solution) - 1; $i++) {
            $initialNode = $solution[$i];
            $finalNode = $solution[$i+1];

            $path = $this->findPath($initialNode->getId(), $finalNode->getId());
            foreach ($this->paths as $path) {
                $path->increasePheromone($solutionPheromoneIncrease);
            }
        }

        foreach ($this->paths as $path) {
            $path->evapore();
        }
    }

    private function makePaths(): array
    {
        $paths = [];

        foreach ($this->nodes as $node) {
            $adjList = $node->getAdjList();

            foreach ($adjList as $adjNode) {
                $path = new Path($node->getId(), $adjNode, $this->initialPheromone, $this->evaporationFee);

                array_push($paths, $path);
            }
        }

        return $paths;
    }

    private function releaseAnt(int $actualPosition = -1)
    {
        $solution = [];
        $tempSolution = [];
        $visited = [];

        do {
            $solution = $tempSolution;
            $visited[] = $actualPosition;
            array_push($tempSolution, $this->getNextNode($actualPosition, $visited));
        } while ($this->verifyStopCondition($tempSolution));

        return $solution;
    }

    private function mapNodes(array $nodes): array
    {
        $mappedNodes = [];

        foreach ($nodes as $node) {
            $mappedNodes[$node->getId()] = $node;
        }

        return $mappedNodes;
    }

    private function getNextNode(int $actualNodeId, array $visited)
    {
        if ($actualNodeId === -1) {
            $firstNodeId = array_rand($this->nodes, 1);
            array_push($visited, $firstNodeId);

            return $this->nodes[$firstNodeId];
        }

        $actualNode = $this->nodes[$actualNodeId];
        $adjNodes = $actualNode->getAdjList();
        $notVisitedNodes = array_intersect($adjNodes, $visited);

        if (!$adjNodes || !$notVisitedNodes) {
            return null;
        }

        /** id => pheromone */
        $mappedPheromones = [];
        foreach ($this->paths as $path) {
            foreach ($notVisitedNodes as $notVisitedNode) {
                if ($path->verifyPath($actualNodeId, $notVisitedNode)) {
                    $potentialNode = $this->nodes[$notVisitedNode];
                    $mappedPheromones[$notVisitedNode] = $potentialNode->getPheromone();
                }
            }
        }
        $total = 0;
        foreach ($mappedPheromones as $mappedPheromone) {
            $total += $mappedPheromone;
        }
        $randValue = rand(0, ceil($total));
        foreach ($mappedPheromones as $nodeId => $mappedPheromone) {
            $randValue -= $mappedPheromone;
            if ($randValue <= 0) {
                return $this->nodes[$nodeId];
            }
        }
        throw new Exception(self::ERROR_TO_FIND_NEXT_NODE);
    }

    private function findPath(int $initialNode, int $finalNode)
    {
        foreach ($this->paths as $path) {
            $pathSearched = $path->verifyPath($initialNode, $finalNode);

            if ($pathSearched) {
                return $path;
            }
        }

        return false;
    }

    abstract protected function verifyStopCondition(array $solution): bool;

    abstract protected function calculatePheromoneIncreaseValue(int $objectiveReached): int;

    protected abstract function calculateSolutionObjectiveValue(array $solution): int;
}
