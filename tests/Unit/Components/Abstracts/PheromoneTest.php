<?php

namespace Tests\Aco\Components\Abstracts;

use PHPUnit\Framework\TestCase;
use Aco\Components\Abstracts\Pheromone;

class PheromoneTest extends TestCase
{
    public function testGetInitialPheromoneReturnsInitialValue()
    {
        $initialPheromone = 5;
        $evaporationFee = 0.5;
        $pheromone = $this->getMockForAbstractClass(Pheromone::class, [$initialPheromone, $evaporationFee]);

        $this->assertEquals($initialPheromone, $pheromone->getInitialPheromone());
    }

    public function testGetEvaporationFeeReturnsValue()
    {
        $initialPheromone = 5;
        $evaporationFee = 0.5;
        $pheromone = $this->getMockForAbstractClass(Pheromone::class, [$initialPheromone, $evaporationFee]);

        $this->assertEquals($evaporationFee, $pheromone->getEvaporationFee());
    }

    public function testCalculatePheromoneIncreaseValueReturnsCorrectValue()
    {
        $initialPheromone = 5;
        $evaporationFee = 0.5;
        $pheromone = $this->getMockForAbstractClass(Pheromone::class, [$initialPheromone, $evaporationFee]);

        /* 
        * Test the calculatePheromoneIncreaseValue method implementation in your concrete class.
        * For this abstract class, the concrete implementation should define how the increase value is calculated.
        * You should test the concrete implementations of the calculatePheromoneIncreaseValue method in your subclasses.
        */
        $objectiveReached = 10.0;
        $this->assertIsInt($pheromone->calculatePheromoneIncreaseValue($objectiveReached));
    }
}
