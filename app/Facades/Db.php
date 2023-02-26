<?php

namespace App\Facades;

use Database\Orm\Mysql;

class Db
{
    /**
     * a very basic Facade
     * Db constructor
     */
    public function __construct()
    {
        $this->db = new Mysql(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    /**
     * provides a way to call some methods statically
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return (new static())->db->{$name}(...$arguments);
    }
}
