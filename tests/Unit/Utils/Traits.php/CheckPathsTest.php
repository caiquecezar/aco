<?php

use PHPUnit\Framework\TestCase;
use CaiqueCezar\Aco\Components\Path;
use CaiqueCezar\Aco\Exceptions\VariableIsNotAPathException;
use CaiqueCezar\Aco\Utils\Traits\CheckPaths;

class CheckPathsTest extends TestCase
{
    use CheckPaths;

    public function testCheckNodesWithValidNodes()
    {
        $path1 = $this->createMock(Path::class);
        $path2 = $this->createMock(Path::class);

        $this->expectNotToPerformAssertions();

        $this->checkPaths([$path1, $path2]);
    }

    public function testCheckNodesWithInvalidNodes()
    {
        $path1 = $this->createMock(Path::class);
        $invalidNode = new \stdClass(); 

        $this->expectException(VariableIsNotAPathException::class);
        $this->checkPaths([$path1, $invalidNode]);
    }

    public function testCheckNodesWithEmptyArray()
    {
        $this->expectNotToPerformAssertions();
        $this->checkPaths([]);
    }
}
