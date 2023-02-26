<?php

namespace App\Models;

use App\Facades\Db;
use PDO;

class Employee extends Model
{
    protected $table = "TblEmployees";
    protected $primaryKey = "id";
    protected $attributesMap = [
        "id" => "EmployeeID",
        "surname" => "Surname",
        "password" => "Password",
    ];

    /**
     * Employee's unique id
     * @var int $id
     */
    public $id;
    /**
     * Employee's surname
     * @var string $surname
     **/

    public $surname;
    /**
     * Hashed als salted password
     * @var string $password
     */
    public $password;

    /**
     * returns all resources as objects of type Machine that are currently checked out by the employee named 'Sandy'
     * Question: I assumed that an employee with name: Sandy must exists
     * it's easy to return an empty result or throw NotFoundException in the cases that there is no employee with that name
     * @param string $employeeSurname
     * @return array
     */
    public function getMachines(string $employeeSurname): array
    {
        $stmt = Db::executeRawQuery(
            "SELECT m.* from TblEmployees e
            JOIN TblMachines m ON e.EmployeeID = m.EmployeeID
            WHERE e.Surname = :employeeSurname",
            ["employeeSurname" => $employeeSurname]
        );
        $machinesRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $machines = [];
        foreach ($machinesRaw as $machineRaw) {
            $machines[] = Machine::_convertRawDataToModelObject($machineRaw);
        }
        return $machines;
    }

    /**
     * returns the list of all machines that ‘Sandy’ checked out as a json encoded array
     * @param string $employeeSurname
     * @return false|string
     */
    public function getMachinesJson(string $employeeSurname)
    {
        return json_encode($this->getMachines($employeeSurname));
    }
}
