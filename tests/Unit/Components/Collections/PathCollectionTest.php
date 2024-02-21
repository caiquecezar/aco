<?php

use PHPUnit\Framework\TestCase;
use CaiqueCezar\Aco\Components\Collections\PathCollection;
use CaiqueCezar\Aco\Components\Path;
use CaiqueCezar\Aco\Exceptions\NextNodeNotFoundException;
use CaiqueCezar\Aco\Components\Abstracts\Pheromone;
use Tests\Utils\Concretes\Default\NodeImpl;
use Tests\Utils\Concretes\Default\PheromoneImpl;
use Tests\Utils\Concretes\Default\SolutionImpl;

class PathCollectionTest extends TestCase
{
    public function testAddPath()
    {
        $pheromone = $this->createMock(Pheromone::class);
        $edgeCollection = new PathCollection([]);
        $path = new Path(1, 2, $pheromone);
        
        $edgeCollection->addPath($path);
        $this->assertCount(1, $edgeCollection->getPaths());
    }

    public function testFindPath()
    {
        $pheromone = $this->createMock(Pheromone::class);

        $path1 = new Path(1, 2, $pheromone);
        $path2 = new Path(2, 3, $pheromone);
        $paths = [$path1, $path2];
        $edgeCollection = new PathCollection($paths);

        $foundPath = $edgeCollection->findPath(1, 2);
        $this->assertEquals($path1, $foundPath);

        $foundPath = $edgeCollection->findPath(2, 3);
        $this->assertEquals($path2, $foundPath);

        $notFoundPath = $edgeCollection->findPath(1, 3);
        $this->assertFalse($notFoundPath);
    }

    public function testFindPathReverse()
    {
        $pheromone = $this->createMock(Pheromone::class);

        $path1 = new Path(1, 2, $pheromone);
        $path2 = new Path(2, 3, $pheromone);
        $paths = [$path1, $path2];
        $edgeCollection = new PathCollection($paths);

        $foundPath = $edgeCollection->findPath(2, 1);
        $this->assertEquals($path1, $foundPath);
    }

    public function testUpdatePheromone()
    {
        $pheromone1 = new PheromoneImpl(10, 0.1);
        $pheromone2 = new PheromoneImpl(100, 0.1);

        $node1 = new NodeImpl(10);
        $node2 = new NodeImpl(10);
        $node3 = new NodeImpl(10);
        $solutionNodes = [$node1, $node2, $node3];
        $solution = new SolutionImpl($solutionNodes);

        $path1 = new Path($node1->getId(), $node2->getId(), $pheromone1);
        $path2 = new Path($node2->getId(), $node3->getId(), $pheromone2);
        $paths = [$path1, $path2];
        $edgeCollection = new PathCollection($paths);

        $edgeCollection->updatePheromone($solution);

        /*
        * Solution objective function adds 0 pheromone then evapore
        */
        $this->assertEquals(9, $path1->getPheromone());
        $this->assertEquals(90, $path2->getPheromone());
    }

    public function testFindNextNodeFollowingPheromone()
    {
        $pheromone = new PheromoneImpl(100, 0.1);

        $node1 = new NodeImpl(10);
        $node2 = new NodeImpl(10);
        $node3 = new NodeImpl(10);

        $path1 = new Path($node1->getId(), $node2->getId(), $pheromone);
        $path2 = new Path($node1->getId(), $node3->getId(), $pheromone);
        $path3 = new Path($node2->getId(), $node3->getId(), $pheromone);
        $paths = [$path1, $path2, $path3];
        $edgeCollection = new PathCollection($paths);

        $nextNode = $edgeCollection->findNextNodeFollowingPheromone(
            $node1->getId(), 
            [$node2->getId(), $node3->getId()]
        );

        $this->assertTrue(in_array($nextNode, [$node2->getId(), $node3->getId()]));
    }

    public function testFindNextNodeFollowingPheromoneThrowsException()
    {
        $pheromone = $this->createMock(Pheromone::class);

        $this->expectException(NextNodeNotFoundException::class);

        $path1 = new Path(1, 2, $pheromone);
        $paths = [$path1];
        $edgeCollection = new PathCollection($paths);

        $edgeCollection->findNextNodeFollowingPheromone(1, [3, 4]);
    }
}
