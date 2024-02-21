<?php

namespace Tests\Aco\Components;

use PHPUnit\Framework\TestCase;
use CaiqueCezar\Aco\Components\Path;
use CaiqueCezar\Aco\Components\Abstracts\Pheromone;

class PathTest extends TestCase
{
    public function testEvaporateMethodReducesPheromoneCorrectly()
    {
        $initialPheromone = 100;
        $evaporationFee = 0.1;
        $initialNode = 1;
        $finalNode = 2;

        $pheromoneMock = $this->createMock(Pheromone::class);
        $pheromoneMock->expects($this->once())
            ->method('getInitialPheromone')
            ->willReturn($initialPheromone);
        $pheromoneMock->expects($this->once())
            ->method('getEvaporationFee')
            ->willReturn($evaporationFee);

        $path = new Path($initialNode, $finalNode, $pheromoneMock);
        $path->evapore();

        $expectedPheromone = (int) ($initialPheromone - $initialPheromone * $evaporationFee);
        $this->assertEquals($expectedPheromone, $path->getPheromone());
    }

    public function testIncreasePheromoneMethodIncreasesPheromoneCorrectly()
    {
        $initialPheromone = 100;
        $solutionValue = 10;

        $pheromoneMock = $this->createMock(Pheromone::class);
        $pheromoneMock->expects($this->once())
            ->method('calculatePheromoneIncreaseValue')
            ->with($this->equalTo($solutionValue))
            ->willReturn(20);
        $pheromoneMock->expects($this->once())
            ->method('getInitialPheromone')
            ->willReturn($initialPheromone);

        $path = new Path(1, 2, $pheromoneMock);
        $path->increasePheromone($solutionValue);

        $expectedPheromone = $initialPheromone + 20;
        $this->assertEquals($expectedPheromone, $path->getPheromone());
    }
}
