<?php

namespace CaiqueCezar\Aco\Components;

use CaiqueCezar\Aco\Components\Abstracts\Solution;
use CaiqueCezar\Aco\Components\Collections\EdgeCollection;
use CaiqueCezar\Aco\Components\Collections\NodeCollection;
use CaiqueCezar\Aco\Components\Factories\SolutionFactory;

class Context
{
    private NodeCollection $nodes;
    private EdgeCollection $edges;
    private string $solutionClass;

    /**
     * Context constructor.
     *
     * @param NodeCollection $nodes Collection of nodes in the context.
     * @param EdgeCollection $edges Collection of edges in the context.
     * @param string $solutionClass string with an implementation of Solution class.
     */
    public function __construct(NodeCollection $nodes, EdgeCollection $edges, string $solutionClass)
    {
        $this->nodes = $nodes;
        $this->edges = $edges;
        $this->solutionClass = $solutionClass;
    }

    /**
     * Releases an ant into paths to explore and find a solution.
     * 
     * @param int $currentNodeId The starting position of the ant. Default is -1.
     * @return Solution The solution obtained by the ant after exploring paths.
     */
    public function releaseAnt(int $currentNodeId = -1): Solution
    {
        $tempSolution = SolutionFactory::createSolution($this->solutionClass);

        /**
         * Getting first node.
         */
        $firstNode = $this->nodes->getNodeById($currentNodeId);
        $tempSolution->addPartialSolution($firstNode);
        $currentNodeId = $firstNode->getId();
        $visited[] = $currentNodeId;

        while (!$tempSolution->isValidSolution()) {
            $notVisitedNodes = $this->nodes->getNotVisitedFrom($currentNodeId, $visited);

            if (!$notVisitedNodes) {
                return $tempSolution;
            }

            $nodeId = $this->edges->findNextNodeFollowingPheromone($currentNodeId, $notVisitedNodes);
            $nextNodeToVisit = $this->nodes->getNodeById($nodeId);
            $tempSolution->addPartialSolution($nextNodeToVisit);

            $currentNodeId = $nextNodeToVisit->getId();
            $visited[] = $currentNodeId;
        }

        return $tempSolution;
    }

    /**
     * Update the pheromone levels of the paths based on the given solution.
     *
     * @param Solution $solution The solution found by the ant.
     * @return void
     */
    public function updatePathsPheromone(Solution $solution): void
    {
        $this->edges->updatePheromone($solution);
    }
}
