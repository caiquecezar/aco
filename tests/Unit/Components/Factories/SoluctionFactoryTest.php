<?php

use PHPUnit\Framework\TestCase;
use CaiqueCezar\Aco\Components\Factories\SolutionFactory;
use CaiqueCezar\Aco\Exceptions\ClassIsNotASolutionInstanceException;
use Tests\Utils\Concretes\Default\SolutionImpl;

class SolutionFactoryTest extends TestCase
{
    public function testCreateSolution()
    {
        $solution = SolutionFactory::createSolution(SolutionImpl::class);
        $this->assertInstanceOf(SolutionImpl::class, $solution);

        $this->expectException(ClassIsNotASolutionInstanceException::class);
        SolutionFactory::createSolution(\stdClass::class);
    }
}
