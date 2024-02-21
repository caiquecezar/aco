<?php

use PHPUnit\Framework\TestCase;
use Aco\Utils\AutoIncrement;

class AutoIncrementTest extends TestCase
{
    public function testNextId()
    {
        $autoIncrement = AutoIncrement::getInstance();

        $firstId = $autoIncrement->nextId();

        $this->assertEquals($firstId + 1, $autoIncrement->nextId());
        $this->assertEquals($firstId + 2, $autoIncrement->nextId());
        $this->assertEquals($firstId + 3, $autoIncrement->nextId());
    }

    public function testSingletonInstance()
    {
        $autoIncrement1 = AutoIncrement::getInstance();
        $autoIncrement2 = AutoIncrement::getInstance();

        $this->assertSame($autoIncrement1, $autoIncrement2);
    }
}
