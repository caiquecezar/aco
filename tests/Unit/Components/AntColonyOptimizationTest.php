<?php

namespace Tests\Components;

use CaiqueCezar\Aco\Components\Abstracts\Solution;
use PHPUnit\Framework\TestCase;
use CaiqueCezar\Aco\Components\AntColonyOptimization;
use CaiqueCezar\Aco\Components\Context;
use CaiqueCezar\Aco\Exceptions\SolutionNotFoundException;

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
