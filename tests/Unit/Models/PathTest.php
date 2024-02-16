<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Aco\Models\Path;
use Aco\Models\Pheromone;
use Tests\Utils\Concretes\Default\PheromoneImpl;

class PathTest extends TestCase
{
    public function testIsCurrentPathShouldReturnTrue()
    {
        $pheromone = new PheromoneImpl(100, 0.01);
        $path = new Path(1, 2, $pheromone);

        $this->assertTrue($path->isCurrentPath(1, 2));
    }

    public function testIsCurrentPathShouldReturnFalse()
    {
        $pheromone = new PheromoneImpl(100, 0.01);
        $path = new Path(1, 2, $pheromone);

        $this->assertFalse($path->isCurrentPath(1, 3));
    }

    public function testGetPheromone()
    {
        $pheromone = new PheromoneImpl(100, 0.01);
        $path = new Path(1, 2, $pheromone);

        $this->assertEquals(100, $path->getPheromone());
    }

    /**
     * @depends testGetPheromone
     */
    public function testEvapore()
    {
        $pheromone = new PheromoneImpl(100, 0.01);
        $path = new Path(1, 2, $pheromone);
        $path->evapore();
        $this->assertEquals(99, $path->getPheromone());
    }

    /**
     * @depends testGetPheromone
     */
    public function testIncreasePheromone()
    {
        $pheromone = new class(100, 0.01) extends Pheromone
        {
            public function calculatePheromoneIncreaseValue(float $objectiveReached): int
            {
                return (int) $objectiveReached * 10;
            }
        };
        $path = new Path(1, 2, $pheromone);
        $path->increasePheromone(1);
        $this->assertEquals(110, $path->getPheromone());
    }
}
