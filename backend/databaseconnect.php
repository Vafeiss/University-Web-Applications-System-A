<?php
/*NAME: Database Connection
Description: This file is responsible for connecting to the advicut database using PDO for error handling
Paraskevas Vafeiadis
26-feb-2026 v0.1
Inputs: None
Outputs: None
Error Messages : if connection fails throw exception with message
Files in use: UsersClass.php where the connection is used to query the database for log in
*/
declare(strict_types=1);

$host = "127.0.0.1";
$db   = "advicut";
$user = "root";
$pass = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //exception handling throws exceptions when errors occurs
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //fetches data as the name not numbers 0,1 etc.
];

$pdo = new PDO($dsn, $user, $pass, $options);
?>
