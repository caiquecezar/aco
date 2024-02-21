<?php

namespace Aco\Utils;

class AutoIncrement
{
    private static ?AutoIncrement $instance = null;
    private int $id;

    /**
     * AutoIncrement constructor.
     *
     * Initializes the id to 0.
     */
    public function __construct()
    {
        $this->id = 0;
    }

    /**
     * Retrieves the singleton instance of AutoIncrement.
     *
     * @return AutoIncrement The singleton instance of AutoIncrement.
     */
    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Retrieves the next incremented id.
     *
     * @return int The next incremented id.
     */
    public function nextId()
    {
        $this->id = $this->id + 1;

        return $this->id;
    }
}
