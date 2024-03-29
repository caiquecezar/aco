<?php

namespace CaiqueCezar\Aco\Components\Collections;

use CaiqueCezar\Aco\Components\Abstracts\Node;
use CaiqueCezar\Aco\Components\Abstracts\Solution;
use CaiqueCezar\Aco\Components\Path;
use CaiqueCezar\Aco\Exceptions\NextNodeNotFoundException;
use CaiqueCezar\Aco\Utils\Traits\CheckPaths;

class PathCollection
{
    use CheckPaths;

    /**
     * A mapped variable with the paths represented by an matrix
     * 
     * $this->paths[initialNode][finalNode] represents one Path
     */
    private array $paths;

    /**
     * PathCollection constructor.
     *
     * @param array $paths An array of paths to initialize the PathCollection.
     */
    public function __construct(array $paths)
    {
        $this->checkPaths($paths);

        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    /**
     * Adds a path to the collection.
     *
     * @param Path $path The path to add.
     * @return void
     */
    public function addPath(Path $path): void
    {
        $this->paths[$path->getInitialNode()][$path->getFinalNode()] = $path;
    }

    /**
     * Retrieves all paths in the collection.
     *
     * @return array All paths in the collection.
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * Find the Path object representing the path between two nodes.
     *
     * @param int $initialNode The ID of the initial node.
     * @param int $finalNode The ID of the final node.
     * @return Path|false The Path object if found, false otherwise.
     */
    public function findPath(int $initialNodeId, int $finalNodeId): Path|false
    {
        if (!empty($this->paths[$initialNodeId][$finalNodeId])) {
            return $this->paths[$initialNodeId][$finalNodeId];
        }

        if (!empty($this->paths[$finalNodeId][$initialNodeId])) {
            return $this->paths[$finalNodeId][$initialNodeId];
        }

        return false;
    }

    /**
     * Updates the pheromone levels along the paths according to the given solution value.
     * 
     * @param Solution $solution The solution.
     * @return void
     */
    public function updatePheromone(Solution $solution): void
    {
        $solutionNodes = $solution->getNodes();
        $solutionValue = $solution->calculateObjective();

        for ($i = 0; $i < sizeOf($solutionNodes) - 1; $i++) {
            /** @var Node $initialNode */
            $initialNode = $solutionNodes[$i];

            /** @var Node $finalNode */
            $finalNode = $solutionNodes[$i + 1];

            $initialNodeId = $initialNode->getId();
            $finalNodeId = $finalNode->getId();

            $path = $this->findPath($initialNodeId, $finalNodeId);

            $path->increasePheromone($solutionValue);
        }

        foreach ($this->paths as $pathsLine) {
            foreach ($pathsLine as $path) {
                /** @var Path $path */
                $path->evapore();
            }
        }
    }

    /**
     * Given an array of pheromones, find randomly a next node to visit.
     * 
     * The method may throw a NextNodeNotFoundException if it fails to find a suitable node.
     * 
     * @param int $fromNode The ID of the current node.
     * @param array $toNodes An array of IDs representing the nodes to choose from.
     * @return int The ID of the next node to visit.
     * @throws NextNodeNotFoundException If no suitable node is found.
     */
    public function findNextNodeFollowingPheromone(int $fromNode, array $toNodes): int
    {
        $mappedPheromones = [];
        foreach ($toNodes as $toNode) {
            $path = $this->findPath($fromNode, $toNode);

            if ($path) {
                $mappedPheromones[$toNode] = $path->getPheromone();
            }
        }

        $totalPheromone = 0;
        foreach ($mappedPheromones as $mappedPheromone) {
            $totalPheromone += $mappedPheromone;
        }

        /** Use a random value to chose next node.
         * 
         * The most pheromone a path to node have, bigger the chance of the node been chosen.
         */
        $randValue = rand(0, ceil($totalPheromone));

        foreach ($mappedPheromones as $nodeId => $mappedPheromone) {
            $randValue -= $mappedPheromone;

            if ($randValue <= 0) {
                return $nodeId;
            }
        }

        throw new NextNodeNotFoundException();
    }
}
