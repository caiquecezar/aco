<?php

use PHPUnit\Framework\TestCase;
use CaiqueCezar\Aco\Components\Collections\NodeCollection;
use Tests\Utils\Concretes\Default\NodeImpl;

class NodeCollectionTest extends TestCase
{
    public function testAddNode()
    {
        $nodeCollection = new NodeCollection([]);
        $node = new NodeImpl(1);
        
        $nodeCollection->addNode($node);
        $this->assertCount(1, $nodeCollection->getNodes());
    }

    public function testGetNodeById()
    {
        $node1 = new NodeImpl(1);
        $node2 = new NodeImpl(2);
        $node3 = new NodeImpl(3);
        $nodes = [$node1, $node2, $node3];
        $nodeCollection = new NodeCollection($nodes);

        $retrievedNode = $nodeCollection->getNodeById($node1->getId());
        $this->assertEquals($node1, $retrievedNode);

        $retrievedNode = $nodeCollection->getNodeById($node2->getId());
        $this->assertEquals($node2, $retrievedNode);

        $retrievedNode = $nodeCollection->getNodeById(-1); 
        $this->assertContains($retrievedNode, $nodes);
    }

    public function testGetNotVisitedFrom()
    {
        $node1 = new NodeImpl(1);
        $node2 = new NodeImpl(2);
        $node3 = new NodeImpl(3);
        $nodes = [$node1, $node2, $node3];
        $nodeCollection = new NodeCollection($nodes);

        $node1->setAdjList([$node2->getId(), $node3->getId()]);

        $node2->setAdjList([$node3->getId()]);

        $notVisitedNodes = $nodeCollection->getNotVisitedFrom($node1->getId(), []);
        $this->assertEquals([$node2->getId(), $node3->getId()], $notVisitedNodes);

        $notVisitedNodes = $nodeCollection->getNotVisitedFrom($node2->getId(), [$node3->getId()]);
        $this->assertEquals([], $notVisitedNodes);

        $notVisitedNodes = $nodeCollection->getNotVisitedFrom($node3->getId(), []);
        $this->assertEquals([], $notVisitedNodes);
    }
}
