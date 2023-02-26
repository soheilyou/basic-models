<?php

require_once "../vendor/autoload.php";

// Load Config
require_once '../app/config.php';

use App\Models\Machine;
use App\Models\Employee;

// create the first employee
$employee1 = new Employee();
$employee1->surname = 'green';
$employee1->save();

// create the second employee
$employee1 = new Employee();
$employee1->surname = 'red';
$employee1->save();

/**
 * MariaDB [test]> select * from TblEmployees;
 * +------------+---------+----------+
 * | EmployeeID | Surname | Password |
 * +------------+---------+----------+
 * |          1 | green   | NULL     |
 * |          2 | red     | NULL     |
 * +------------+---------+----------+
 */

// create the first machine
$machine1 = new Machine();
$machine1->title = 'foo';
$machine1->save();

// create the second machine
$machine1 = new Machine();
$machine1->title = 'bar';
$machine1->save();

// create the third machine
$machine1 = new Machine();
$machine1->title = 'truck';
$machine1->save();

/**
 * MariaDB [test]> select * from TblMachines;
 * +-----------+-------+------------+
 * | MachineID | Title | EmployeeID |
 * +-----------+-------+------------+
 * |         1 | foo   |       NULL |
 * |         2 | bar   |       NULL |
 * |         3 | truck |       NULL |
 * +-----------+-------+------------+
 */


$greenEmployee = Employee::_find(1);
$foo = Machine::_find(1);
$foo->checkout($greenEmployee);

/**
 * MariaDB [test]> select * from TblMachines;
 * +-----------+-------+------------+
 * | MachineID | Title | EmployeeID |
 * +-----------+-------+------------+
 * |         1 | foo   |          1 |
 * |         2 | bar   |       NULL |
 * |         3 | truck |       NULL |
 * +-----------+-------+------------+
 */
$e = new Employee();
dump($e->getMachines('green'));
// result:
/**
^ array:1 [
    0 => App\Models\Machine^ {#8
    #table: "TblMachines"
    #primaryKey: "id"
    #attributesMap: array:3 [
    "id" => "MachineID"
      "employeeId" => "EmployeeID"
      "title" => "Title"
    ]
    +id: "1"
    +employeeId: "1"
    +title: "foo"
    #originalAttributes: array:3 [
      "id" => "1"
      "employeeId" => "1"
      "title" => "foo"
    ]
    #attributes: array:3 [
      0 => "id"
      1 => "employeeId"
      2 => "title"
    ]
  }
]
 **/
dump($e->getMachinesJson('green'));
// result:
/**
 * [{"id":"1","employeeId":"1","title":"foo"}]
 */
$bar = Machine::_find(2);
$bar->checkout($greenEmployee);
/**
 * MariaDB [test]> select * from TblMachines;
 * +-----------+-------+------------+
 * | MachineID | Title | EmployeeID |
 * +-----------+-------+------------+
 * |         1 | foo   |          1 |
 * |         2 | bar   |          1 |
 * |         3 | truck |       NULL |
 * +-----------+-------+------------+
 */
dump($e->getMachines('green'));
/**
^ array:2 [
    0 => App\Models\Machine^ {#14
    #table: "TblMachines"
    #primaryKey: "id"
    #attributesMap: array:3 [
    "id" => "MachineID"
      "employeeId" => "EmployeeID"
      "title" => "Title"
    ]
    +id: "1"
    +employeeId: "1"
    +title: "foo"
    #originalAttributes: array:3 [
      "id" => "1"
      "employeeId" => "1"
      "title" => "foo"
    ]
    #attributes: array:3 [
      0 => "id"
      1 => "employeeId"
      2 => "title"
    ]
  }
  1 => App\Models\Machine^ {#16
    #table: "TblMachines"
    #primaryKey: "id"
    #attributesMap: array:3 [
    "id" => "MachineID"
      "employeeId" => "EmployeeID"
      "title" => "Title"
    ]
    +id: "2"
    +employeeId: "1"
    +title: "bar"
    #originalAttributes: array:3 [
      "id" => "2"
      "employeeId" => "1"
      "title" => "bar"
    ]
    #attributes: array:3 [
      0 => "id"
      1 => "employeeId"
      2 => "title"
    ]
  }
]
*/
dump($e->getMachinesJson('green'));
// result
/**
 *  "[{"id":"1","employeeId":"1","title":"foo"},{"id":"2","employeeId":"1","title":"bar"}]"
 */

$foo->back_to_warehouse();
/**
 * MariaDB [test]> select * from TblMachines;
 * +-----------+-------+------------+
 * | MachineID | Title | EmployeeID |
 * +-----------+-------+------------+
 * |         1 | foo   |       NULL |
 * |         2 | bar   |          1 |
 * |         3 | truck |       NULL |
 * +-----------+-------+------------+
 */
dump($e->getMachinesJson('green'));
// result
/**
 * ^ "[{"id":"2","employeeId":"1","title":"bar"}]"
 */

