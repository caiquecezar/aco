<?php

namespace Tests\Aco\Components\Abstracts;

use PHPUnit\Framework\TestCase;
use CaiqueCezar\Aco\Components\Abstracts\Node;
use CaiqueCezar\Aco\Utils\AutoIncrement;

class NodeTest extends TestCase
{
    public function testGetAdjListReturnsEmptyArrayByDefault()
    {
        $node = $this->getMockForAbstractClass(Node::class);
        $this->assertSame([], $node->getAdjList());
    }

    public function testSetAdjListSetsAdjacencyList()
    {
        $node = $this->getMockForAbstractClass(Node::class);
        $adjList = [1, 2, 3];
        $node->setAdjList($adjList);
        $this->assertSame($adjList, $node->getAdjList());
    }

    public function testGetIdReturnsUniqueId()
    {
        $node1 = $this->getMockForAbstractClass(Node::class);
        $node2 = $this->getMockForAbstractClass(Node::class);
        $this->assertNotEquals($node1->getId(), $node2->getId());
    }

    public function testGetIdReturnsIncrementalValues()
    {
        $autoIncrement = AutoIncrement::getInstance();
        $node1 = $this->getMockForAbstractClass(Node::class);
        $node2 = $this->getMockForAbstractClass(Node::class);
        $this->assertEquals($autoIncrement->nextId() - 2, $node1->getId());
        $this->assertEquals($autoIncrement->nextId() - 2, $node2->getId());
    }
}
