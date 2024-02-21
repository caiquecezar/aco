<?php

use PHPUnit\Framework\TestCase;
use Aco\Utils\Traits\CheckNodes;
use Aco\Components\Abstracts\Node;
use Aco\Exceptions\VariableIsNotANodeException;

class CheckNodesTest extends TestCase
{
    use CheckNodes;

    public function testCheckNodesWithValidNodes()
    {
        $node1 = $this->createMock(Node::class);
        $node2 = $this->createMock(Node::class);

        $this->expectNotToPerformAssertions();

        $this->checkNodes([$node1, $node2]);
    }

    public function testCheckNodesWithInvalidNodes()
    {
        $node1 = $this->createMock(Node::class);
        $invalidNode = new \stdClass(); 

        $this->expectException(VariableIsNotANodeException::class);
        $this->checkNodes([$node1, $invalidNode]);
    }

    public function testCheckNodesWithEmptyArray()
    {

        $this->expectNotToPerformAssertions();
        $this->checkNodes([]);
    }
}
