<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Tests\Utils\Concretes\Default\NodeImpl;
use Tests\Utils\Concretes\Default\SolutionImpl;

class SolutionTest extends TestCase
{
    public function testBuildSolutionShouldReturnInstaceOfSolutionWithNodes()
    {
        $nodes = [
            new NodeImpl(1),
            new NodeImpl(2),
            new NodeImpl(3),
        ];
        $solution = new SolutionImpl();
        $solutionObject = $solution->buildSolution($nodes);
        $solutionNodes = $solutionObject->getNodes();

        $this->assertInstanceOf(SolutionImpl::class, $solutionObject);
        $this->assertEquals($nodes, $solutionNodes);
    }

}
