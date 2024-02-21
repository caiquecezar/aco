<?php

namespace Tests\Aco\Components\Abstracts;

use PHPUnit\Framework\TestCase;
use CaiqueCezar\Aco\Components\Abstracts\Solution;
use CaiqueCezar\Aco\Components\Abstracts\Node;

class SolutionTest extends TestCase
{
    public function testGetNodesReturnsEmptyArrayByDefault()
    {
        $solution = $this->getMockForAbstractClass(Solution::class);
        $this->assertIsArray($solution->getNodes());
        $this->assertEmpty($solution->getNodes());
    }

    public function testAddPartialSolutionAddsNode()
    {
        $solution = $this->getMockForAbstractClass(Solution::class);
        $node = $this->createMock(Node::class);
        $solution->addPartialSolution($node);

        $this->assertCount(1, $solution->getNodes());
    }

    /*
    * You should test the abstract methods calculateObjective() and isValidSolution()
    * in concrete implementations of the Solution class.
    * Here, you only verify if the abstract methods are declared.
    */ 
}
