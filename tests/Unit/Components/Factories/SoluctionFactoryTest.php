<?php

use PHPUnit\Framework\TestCase;
use Aco\Components\Factories\SolutionFactory;
use Aco\Exceptions\ClassIsNotASolutionInstanceException;
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
