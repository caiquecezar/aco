<?php

namespace Tests\Aco\Components\Builders;

use PHPUnit\Framework\TestCase;
use Aco\Components\Builders\ContextBuilder;
use Aco\Components\Collections\NodeCollection;
use Aco\Components\Collections\EdgeCollection;
use Aco\Components\Abstracts\Pheromone;
use Aco\Exceptions\ContextNodesNotFoundException;
use Aco\Exceptions\ContextPathsNotFoundException;
use Aco\Exceptions\ContextSolutionClassNotFoundException;
use Aco\Components\Abstracts\Node;
use Aco\Components\Path;
use ReflectionClass;

class ContextBuilderTest extends TestCase
{
    public function testAddNodes()
    {
        $nodes = new NodeCollection([]);
        $builder = ContextBuilder::builder()->addNodes($nodes);

        $reflectionBuilder = new ReflectionClass('Aco\Components\Builders\ContextBuilder');
        $reflectionNodes = $reflectionBuilder->getProperty('nodes');
        $reflectionNodes->setAccessible(true);

        $this->assertSame($nodes, $reflectionNodes->getValue($builder));
    }

    public function testAddNodesFromArray()
    {
        $nodes = [$this->getMockForAbstractClass(Node::class), $this->getMockForAbstractClass(Node::class)];
        $builder = ContextBuilder::builder()->addNodesFromArray($nodes);

        $reflectionBuilder = new ReflectionClass('Aco\Components\Builders\ContextBuilder');
        $reflectionNodes = $reflectionBuilder->getProperty('nodes');
        $reflectionNodes->setAccessible(true);

        $this->assertInstanceOf(NodeCollection::class, $reflectionNodes->getValue($builder));
    }

    public function testCreatePathsWithPheromone()
    {
        $pheromone = $this->createMock(Pheromone::class);
        $pheromone->method('getInitialPheromone')->willReturn(10);

        $builder = ContextBuilder::builder()
            ->addNodesFromArray([
                $this->getMockForAbstractClass(Node::class), 
                $this->getMockForAbstractClass(Node::class)
            ])->createPaths($pheromone)
            ->addSolution('SolutionClass');

        $reflectionBuilder = new ReflectionClass('Aco\Components\Builders\ContextBuilder');
        $reflectionPaths = $reflectionBuilder->getProperty('edges');
        $reflectionPaths->setAccessible(true);

        $paths = $reflectionPaths->getValue($builder);
        $arrayPaths = $paths->getPaths();

        $this->assertInstanceOf(EdgeCollection::class, $paths);

        $this->assertEquals(10, $arrayPaths[0]->getPheromone());
    }

    public function testAddPaths()
    {
        $edges = new EdgeCollection([]);
        $builder = ContextBuilder::builder()->addPaths($edges);

        $reflectionBuilder = new ReflectionClass('Aco\Components\Builders\ContextBuilder');
        $reflectionPaths = $reflectionBuilder->getProperty('edges');
        $reflectionPaths->setAccessible(true);

        $this->assertSame($edges, $reflectionPaths->getValue($builder));
        $this->assertInstanceOf(EdgeCollection::class, $reflectionPaths->getValue($builder));
    }

    public function testAddPathsFromArray()
    {
        $edges = [new Path(1, 2, $this->createMock(Pheromone::class))];
        $builder = ContextBuilder::builder()->addPathsFromArray($edges);

        $reflectionBuilder = new ReflectionClass('Aco\Components\Builders\ContextBuilder');
        $reflectionPaths = $reflectionBuilder->getProperty('edges');
        $reflectionPaths->setAccessible(true);

        $this->assertInstanceOf(EdgeCollection::class, $reflectionPaths->getValue($builder));
    }

    public function testBuildThrowsExceptionIfNodesNotProvided()
    {
        $this->expectException(ContextNodesNotFoundException::class);
        ContextBuilder::builder()->build();
    }

    public function testBuildThrowsExceptionIfPathsNotProvided()
    {
        $this->expectException(ContextPathsNotFoundException::class);
        ContextBuilder::builder()
            ->addNodesFromArray([$this->getMockForAbstractClass(Node::class)])
            ->build();
    }

    public function testBuildThrowsExceptionIfSolutionClassNotProvided()
    {
        $this->expectException(ContextSolutionClassNotFoundException::class);
        ContextBuilder::builder()
            ->addNodesFromArray([$this->getMockForAbstractClass(Node::class)])
            ->addPathsFromArray([new Path(1, 2, $this->createMock(Pheromone::class))])
            ->build();
    }

    public function testAddSolution()
    {
        $builder = ContextBuilder::builder()->addSolution('SolutionClass');
        $reflectionBuilder = new ReflectionClass('Aco\Components\Builders\ContextBuilder');
        $reflectionPaths = $reflectionBuilder->getProperty('solutionClass');
        $reflectionPaths->setAccessible(true);

        $this->assertEquals('SolutionClass', $reflectionPaths->getValue($builder));
    }

    public function testBuildAdjList()
    {
        $nodes = [
            $this->getMockForAbstractClass(Node::class),
            $this->getMockForAbstractClass(Node::class),
            $this->getMockForAbstractClass(Node::class)
        ];
        $builder = new ContextBuilder();
        $nodesWithAdjList = $builder->buildAdjList($nodes);
        foreach ($nodesWithAdjList as $node) {
            $this->assertCount(2, $node->getAdjList());
        }
    }
}
