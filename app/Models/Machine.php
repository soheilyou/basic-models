<?php

namespace App\Models;

class Machine extends Model
{
    protected $table = "TblMachines";
    protected $primaryKey = "id";
    protected $attributesMap = [
        "id" => "MachineID",
        "employeeId" => "EmployeeID",
        "title" => "Title",
    ];

    /** Machine's unique id
     * @var int $id
     */
    public $id;

    /**
     * Employee id: references the employee's EmployeeId who has borrowed this machine
     * null value means the the machine is not borrowed
     * @var int|null $employee_id
     */
    public $employeeId;

    /**
     * Machine's title
     * @var string $title
     */
    public $title;

    /**
     * assigns the machine to the given employee (checks the machine out)
     * @param Employee $employee the employee who wants to check out the machine
     */
    public function checkout(Employee $employee): void
    {
        // each machine can only be borrowed by one employee at a time.
        $this->employeeId = $employee->id;
        $this->save();
    }

    /**
     * Indicates that no employee has taken the machine with them
     * and that the employee put the machine back to the warehouse
     */
    public function back_to_warehouse(): void
    {
        $this->employeeId = null; // null value means the the machine is not borrowed
        $this->save();
    }
}
