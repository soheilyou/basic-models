<?php

namespace Database\Orm;

use PDO;

class Mysql implements OrmInterface
{
    private PDO $conn;

    /**
     * Mysql constructor.
     * @param $servername
     * @param $username
     * @param $password
     * @param $dbname
     */
    public function __construct($servername, $username, $password, $dbname)
    {
        $this->conn = new PDO(
            "mysql:host=$servername;dbname=$dbname",
            $username,
            $password
        );
        // set the PDO error mode to exception
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * returns all matched records
     * TODO :: use bindParam to make this safe
     * @param string $table
     * @param array $where
     * @return array
     */
    public function get(string $table, array $where = [])
    {
        $whereStm = "";
        if (!empty($where)) {
            $whereStm = "WHERE " . $this->generateStm($where);
        }

        $stmt = $this->executeRawQuery("SELECT * FROM {$table} {$whereStm}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * returns the first matched record
     * @param string $table
     * @param array $where
     * @return mixed
     */
    public function first(string $table, array $where = [])
    {
        $whereStm = "";
        if (!empty($where)) {
            $whereStm = "WHERE " . $this->generateStm($where);
        }

        $stmt = $this->executeRawQuery(
            "SELECT * FROM {$table} {$whereStm} LIMIT 1"
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $table
     * @param array $data
     * @return false|\PDOStatement
     */
    public function insert(string $table, array $data)
    {
        $columns = array_keys($data);
        $values = array_values($data);
        return $this->executeRawQuery(
            "INSERT INTO {$table} (" .
                implode(", ", $columns) .
                ") VALUES ('" .
                implode("', '", $values) .
                "')"
        );
    }

    /**
     * convert array to query statement
     * @param array $data
     * @return string
     */
    protected function generateStm(array $data): string
    {
        $map = [];
        $stm = array_walk($data, function ($value, $key) use (&$map) {
            $valueStm = is_null($value) ? "NULL" : "'$value'";
            $map[] = "$key = $valueStm";
        });
        return implode(", ", $map);
    }

    /**
     * TODO :: use bindParam
     * @param string $table
     * @param array $data
     * @param array $where
     * @return false|\PDOStatement
     */
    public function update(string $table, array $data, array $where = [])
    {
        $updateStm = $this->generateStm($data);
        $whereStm = "";
        if (!empty($where)) {
            $whereStm = "WHERE " . $this->generateStm($where);
        }
        return $this->executeRawQuery(
            "UPDATE {$table} SET {$updateStm} {$whereStm}"
        );
    }

    /**
     * @param string $statement
     * @param array $params
     * @return false|\PDOStatement
     */
    public function executeRawQuery(string $statement, array $params = [])
    {
        $stmt = $this->conn->prepare($statement);
        foreach ($params as $param => $value) {
            $stmt->bindParam(":$param", $value);
        }
        $stmt->execute();
        return $stmt;
    }
}
