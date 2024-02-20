<?php

namespace Tests\Components;

use Aco\Components\Abstracts\Solution;
use PHPUnit\Framework\TestCase;
use Aco\Components\AntColonyOptimization;
use Aco\Components\Context;
use Aco\Exceptions\SolutionNotFoundException;

class AntColonyOptimizationTest extends TestCase
{
    public function testRunMethodReturnsSolution()
    {
        $solutionMock = $this->createMock(Solution::class);
        $solutionMock->expects($this->exactly(10))
            ->method('isValidSolution')
            ->willReturn(true);
        $solutionMock->expects($this->exactly(10))
            ->method('calculateObjective')
            ->willReturn(10.0);

        $contextMock = $this->createMock(Context::class);
        $contextMock->expects($this->exactly(10))
            ->method('releaseAnt')
            ->willReturn($solutionMock);
        $contextMock->expects($this->exactly(10))
            ->method('updatePathsPheromone');

        $totalAnts = 10;
        $aco = new AntColonyOptimization($contextMock, $totalAnts);

        $this->assertInstanceOf(Solution::class, $aco->run());
    }

    /**
     * @test
     */
    public function testRunMethodThrowsExceptionWhenNoSolutionFound()
    {
        $solutionMock = $this->createMock(Solution::class);
        $solutionMock->expects($this->exactly(10))
            ->method('isValidSolution')
            ->willReturn(false);
        $solutionMock->expects($this->exactly(0))
            ->method('calculateObjective');

        $contextMock = $this->createMock(Context::class);
        $contextMock->expects($this->exactly(10))
            ->method('releaseAnt')
            ->willReturn($solutionMock);

        $totalAnts = 10;
        $aco = new AntColonyOptimization($contextMock, $totalAnts);

        $this->expectException(SolutionNotFoundException::class);
        $aco->run();
    }
}
