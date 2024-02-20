<?php

namespace Aco\Components\Collections;

use Aco\Components\Abstracts\Node;
use Aco\Components\Path;
use Aco\Exceptions\NextNodeNotFoundException;
use Aco\Utils\Traits\CheckPaths;

class EdgeCollection
{
    use CheckPaths;

    private array $paths;

    /**
     * EdgeCollection constructor.
     *
     * @param array $paths An array of paths to initialize the EdgeCollection.
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
        $this->paths[] = $path;
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
    public function findPath(int $initialNodeId, int $finalNodeId): Path
    {
        foreach ($this->paths as $path) {
            /** @var Path $path*/
            $pathSearched = $path->isCurrentPath($initialNodeId, $finalNodeId) || $path->isCurrentPath($finalNodeId, $initialNodeId);

            if ($pathSearched) {
                return $path;
            }
        }

        return false;
    }

    /**
     * Updates the pheromone levels along the paths according to the given solution value.
     * 
     * @param array $solution The solution path.
     * @param float $solutionValue The value of the solution.
     * @return void
     */
    public function updatePheromone(array $solution, float $solutionValue): void
    {
        for ($i = 0; $i < sizeOf($solution) - 1; $i++) {
            /** @var Node $initialNode */
            $initialNode = $solution[$i];

            /** @var Node $finalNode */
            $finalNode = $solution[$i + 1];

            $initialNodeId = $initialNode->getId();
            $finalNodeId = $finalNode->getId();

            foreach ($this->paths as $path) {
                /** @var Path $path */
                $pathShouldIncreasePheromone = $path->isCurrentPath($initialNodeId, $finalNodeId) || $path->isCurrentPath($finalNodeId, $initialNodeId);

                if ($pathShouldIncreasePheromone) {
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
