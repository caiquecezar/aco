<?php

namespace Tests\Aco\Components;

use Aco\Components\Abstracts\Node;
use PHPUnit\Framework\TestCase;
use Aco\Components\Context;
use Aco\Components\Collections\NodeCollection;
use Aco\Components\Collections\EdgeCollection;
use Aco\Components\Abstracts\Solution;
use Tests\Utils\Concretes\Default\NodeImpl;
use Tests\Utils\Concretes\Default\SolutionImpl;
use Tests\Utils\Concretes\Solutions\SolutionImpl2Nodes;

class ContextTest extends TestCase
{
    public function testReleaseAntReturnsValidSolutionWithOneNode()
    {
        $nodeMock = $this->createMock(NodeImpl::class);
        $nodeMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $nodeCollectionMock = $this->createMock(NodeCollection::class);
        $nodeCollectionMock->expects($this->once())
            ->method('getNodeById')
            ->with(-1)
            ->willReturnOnConsecutiveCalls($nodeMock);

        $edgeCollectionMock = $this->createMock(EdgeCollection::class);

        $context = new Context($nodeCollectionMock, $edgeCollectionMock, SolutionImpl::class);

        $solution = $context->releaseAnt();

        $this->assertInstanceOf(Solution::class, $solution);
    }

    public function testReleaseAntReturnsValidSolutionWithTwoNodes()
    {
        $nodeMock = $this->createMock(NodeImpl::class);
        $nodeMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $nodeMock2 = $this->createMock(NodeImpl::class);
        $nodeMock2->expects($this->once())
            ->method('getId')
            ->willReturn(2);

        $nodeCollectionMock = $this->createMock(NodeCollection::class);
        $nodeCollectionMock->expects($this->exactly(2))
            ->method('getNodeById')
            ->willReturnOnConsecutiveCalls($nodeMock, $nodeMock2);
        $nodeCollectionMock->expects($this->once())
            ->method('getNotVisitedFrom')
            ->with(1, [1])
            ->willReturnOnConsecutiveCalls([2]);

        $edgeCollectionMock = $this->createMock(EdgeCollection::class);
        $edgeCollectionMock->expects($this->once())
            ->method('findNextNodeFollowingPheromone')
            ->with(1, [2])
            ->willReturnOnConsecutiveCalls(2);

        $context = new Context($nodeCollectionMock, $edgeCollectionMock, SolutionImpl2Nodes::class);

        $solution = $context->releaseAnt();

        $this->assertInstanceOf(Solution::class, $solution);
    }

    public function testReleaseAntReturnsInvalidSolutionWhenNotFoundNodesToVisit()
    {
        $nodeMock = $this->createMock(NodeImpl::class);
        $nodeMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $nodeCollectionMock = $this->createMock(NodeCollection::class);
        $nodeCollectionMock->expects($this->exactly(1))
            ->method('getNodeById')
            ->willReturnOnConsecutiveCalls($nodeMock);
        $nodeCollectionMock->expects($this->once())
            ->method('getNotVisitedFrom')
            ->with(1, [1])
            ->willReturnOnConsecutiveCalls([]);

        $edgeCollectionMock = $this->createMock(EdgeCollection::class);

        $context = new Context($nodeCollectionMock, $edgeCollectionMock, SolutionImpl2Nodes::class);

        $solution = $context->releaseAnt();

        $this->assertInstanceOf(Solution::class, $solution);
        $this->assertFalse($solution->isValidSolution());
    }

    public function testUpdatePathsPheromoneMethodUpdatesPheromone()
    {
        $nodeCollectionMock = $this->createMock(NodeCollection::class);

        $edgeCollectionMock = $this->createMock(EdgeCollection::class);

        $solutionMock = $this->createMock(Solution::class);

        $context = new Context($nodeCollectionMock, $edgeCollectionMock, Solution::class);

        $edgeCollectionMock->expects($this->once())
            ->method('updatePheromone')
            ->with($solutionMock);

        $context->updatePathsPheromone($solutionMock);
    }
}
