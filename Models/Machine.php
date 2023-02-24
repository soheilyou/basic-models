<?php


class Machine
{
    /** Machine's unique id
     * @var int $id
     */
    public int $id;

    /**
     * Machine's title
     * @var string $title
     */
    public string $title;

    /**
     * assigns the machine to the given employee (checks the machine out)
     * @param Employee $employee the employee who wants to check out the machine
     */
    public function checkout(Employee $employee): void
    {
    }

    /**
     * Indicates that no employee has taken the machine with them
     * and that the employee put the machine back to the warehouse
     */
    public function back_to_warehouse(): void
    {
    }
}