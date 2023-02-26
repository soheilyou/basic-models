<?php

namespace Database\Orm;

interface OrmInterface
{
    public function first(string $table, array $where = []);
    public function get(string $table, array $where = []);
    public function insert(string $table, array $data);
    public function update(string $table, array $data, array $where = []);
    public function executeRawQuery(string $statement, array $params = []);
}
