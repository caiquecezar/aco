<?php

namespace Aco\Helpers;

abstract class AutoIncrement
{
    private AutoIncrement $instance;
    private int $id;

    public function __construct()
    {
        $this->id = 0;
    }

    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function nextId()
    {
        $this->id = $this->id + 1;

        return $this->id;
    }
}
