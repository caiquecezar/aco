<?php

namespace Tests\Aco\Components\Builders;

use PHPUnit\Framework\TestCase;
use CaiqueCezar\Aco\Components\Builders\ContextBuilder;
use CaiqueCezar\Aco\Components\Collections\NodeCollection;
use CaiqueCezar\Aco\Components\Collections\PathCollection;
use CaiqueCezar\Aco\Components\Abstracts\Pheromone;
use CaiqueCezar\Aco\Exceptions\ContextNodesNotFoundException;
use CaiqueCezar\Aco\Exceptions\ContextPathsNotFoundException;
use CaiqueCezar\Aco\Exceptions\ContextSolutionClassNotFoundException;
use CaiqueCezar\Aco\Components\Abstracts\Node;
use CaiqueCezar\Aco\Components\Context;
use CaiqueCezar\Aco\Components\Path;
use ReflectionClass;
use Tests\Utils\Concretes\Default\NodeImpl;

class ContextBuilderTest extends TestCase
{
    public function testAddNodes()
    {
        $nodes = new NodeCollection([]);
        $builder = ContextBuilder::builder()->addNodes($nodes);

        $reflectionBuilder = new ReflectionClass('CaiqueCezar\Aco\Components\Builders\ContextBuilder');
        $reflectionNodes = $reflectionBuilder->getProperty('nodes');
        $reflectionNodes->setAccessible(true);

        $this->assertSame($nodes, $reflectionNodes->getValue($builder));
    }

    public function testAddNodesFromArray()
    {
        $nodes = [$this->getMockForAbstractClass(Node::class), $this->getMockForAbstractClass(Node::class)];
        $builder = ContextBuilder::builder()->addNodesFromArray($nodes);

        $reflectionBuilder = new ReflectionClass('CaiqueCezar\Aco\Components\Builders\ContextBuilder');
        $reflectionNodes = $reflectionBuilder->getProperty('nodes');
        $reflectionNodes->setAccessible(true);

        $this->assertInstanceOf(NodeCollection::class, $reflectionNodes->getValue($builder));
    }

    public function testCreatePathsWithPheromone()
    {
        $pheromone = $this->createMock(Pheromone::class);
        $pheromone->method('getInitialPheromone')->willReturn(10);
        $node1 = new NodeImpl(1);
        $node2 = new NodeImpl(2);
        $builder = ContextBuilder::builder()
            ->addNodesFromArray([
                $node1,
                $node2
            ])->createPaths($pheromone)
            ->addSolution('SolutionClass');

        $reflectionBuilder = new ReflectionClass('CaiqueCezar\Aco\Components\Builders\ContextBuilder');
        $reflectionPaths = $reflectionBuilder->getProperty('paths');
        $reflectionPaths->setAccessible(true);

        $paths = $reflectionPaths->getValue($builder);
        $arrayPaths = $paths->getPaths();

        $this->assertInstanceOf(PathCollection::class, $paths);

        $this->assertEquals(10, $arrayPaths[$node1->getId()][$node2->getId()]->getPheromone());
    }

    public function testAddPaths()
    {
        $paths = new PathCollection([]);
        $builder = ContextBuilder::builder()->addPaths($paths);

        $reflectionBuilder = new ReflectionClass('CaiqueCezar\Aco\Components\Builders\ContextBuilder');
        $reflectionPaths = $reflectionBuilder->getProperty('paths');
        $reflectionPaths->setAccessible(true);

        $this->assertSame($paths, $reflectionPaths->getValue($builder));
        $this->assertInstanceOf(PathCollection::class, $reflectionPaths->getValue($builder));
    }

    public function testAddPathsFromArray()
    {
        $paths = [new Path(1, 2, $this->createMock(Pheromone::class))];
        $builder = ContextBuilder::builder()->addPathsFromArray($paths);

        $reflectionBuilder = new ReflectionClass('CaiqueCezar\Aco\Components\Builders\ContextBuilder');
        $reflectionPaths = $reflectionBuilder->getProperty('paths');
        $reflectionPaths->setAccessible(true);

        $this->assertInstanceOf(PathCollection::class, $reflectionPaths->getValue($builder));
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
        $reflectionBuilder = new ReflectionClass('CaiqueCezar\Aco\Components\Builders\ContextBuilder');
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

    public function testContextBuilderCreatesAContextInstance()
    {
        $pheromone = $this->createMock(Pheromone::class);
        $pheromone->method('getInitialPheromone')->willReturn(10);
        $node1 = new NodeImpl(1);
        $node2 = new NodeImpl(2);
        $context = ContextBuilder::builder()
            ->addNodesFromArray([
                $node1,
                $node2
            ])->createPaths($pheromone)
            ->addSolution('SolutionClass')
            ->build();

        $this->assertInstanceOf(Context::class, $context);
    }
}
