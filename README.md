import these tables into your database
```
CREATE TABLE TblEmployees (
EmployeeID int NOT NULL AUTO_INCREMENT,
Surname varchar(255),
Password varchar(255),
PRIMARY KEY (EmployeeID)
);
```
```
CREATE TABLE TblMachines (
MachineID int NOT NULL AUTO_INCREMENT,
Title varchar(255),
EmployeeID int,
FOREIGN KEY (EmployeeID) REFERENCES TblEmployees (EmployeeID)
       ON DELETE CASCADE ,
PRIMARY KEY (MachineID)
);
```
you can see the basic examples in the public/index.php
```
cd public
php index.php
```