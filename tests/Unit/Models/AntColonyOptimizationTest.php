<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Aco\Exceptions\PathsNotFoundException;
use Aco\Exceptions\SolutionNotFoundException;
use ReflectionClass;
use Tests\Utils\Concretes\Default\AntColonyOptimizationImpl;
use Tests\Utils\Concretes\Default\NodeImpl;
use Tests\Utils\Concretes\Default\PheromoneImpl;
use Tests\Utils\Concretes\Default\SolutionImpl;
use Tests\Utils\Traits\CreateDefaultImpl;

class AntColonyOptimizationTest extends TestCase
{
    public function testAntColonyOptimizationCanBeConstructed()
    {
        $nodes = [
            new NodeImpl(1),
            new NodeImpl(2),
            new NodeImpl(3),
        ];
        $pheromone = new PheromoneImpl(100, 0.01);
        $totalAnts = 10;
        $solution = new SolutionImpl();

        $aco = new AntColonyOptimizationImpl($nodes, $pheromone, $totalAnts, $solution);

        $this->assertInstanceOf(AntColonyOptimizationImpl::class, $aco);
    }

    public function testRunReturnsBestSolution()
    {
        $nodes = [
            new NodeImpl(1),
            new NodeImpl(2),
            new NodeImpl(3),
        ];
        $pheromone = new PheromoneImpl(100, 0.01);
        $totalAnts = 10;
        $solution = new SolutionImpl();

        $aco = new AntColonyOptimizationImpl($nodes, $pheromone, $totalAnts, $solution);

        $result = $aco->run();

        $this->assertInstanceOf(SolutionImpl::class, $result);
    }

    public function testMakePathsConstructsPathsCorrectly()
    {
        $nodes = [
            new NodeImpl(1),
            new NodeImpl(2),
            new NodeImpl(3),
        ];
        $pheromone = new PheromoneImpl(100, 0.01);
        $totalAnts = 10;
        $solution = new SolutionImpl();

        $aco = new AntColonyOptimizationImpl($nodes, $pheromone, $totalAnts, $solution);

        $reflection = new ReflectionClass('Aco\Models\AntColonyOptimization');
        $atributo = $reflection->getProperty('paths');
        $atributo->setAccessible(true);

        $paths = $atributo->getValue($aco);

        $this->assertCount(6, $paths);
    }

    public function testRunThrowsPathsNotFoundExceptionWhenNoPathsAvailable()
    {
        $nodes = [];
        $pheromone = new PheromoneImpl(100, 0.01);
        $totalAnts = 10;
        $solution = new SolutionImpl();

        $aco = new AntColonyOptimizationImpl($nodes, $pheromone, $totalAnts, $solution);

        $this->expectException(PathsNotFoundException::class);

        $aco->run();
    }

    public function testRunThrowsSolutionNotFoundExceptionWhenNoSolutionFound()
    {
        $nodes = [
            new NodeImpl(1),
            new NodeImpl(2),
            new NodeImpl(3),
        ];
        $pheromone = new PheromoneImpl(100, 0.01);
        $totalAnts = 0;
        $solution = new SolutionImpl();

        $aco = new AntColonyOptimizationImpl($nodes, $pheromone, $totalAnts, $solution);

        $this->expectException(SolutionNotFoundException::class);

        $aco->run();
    }
}
