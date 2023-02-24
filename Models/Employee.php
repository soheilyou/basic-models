<?php


class Employee
{
    /**
     * Employee's unique id
     * @var int $id
     */
    public int $id;
    /**
     * Employee's surname
     * @var string $surname
     **/

    public string $surname;
    /**
     * Hashed als salted password
     * @var string $password
     */
    public string $password;
}