<?php
/*
Author: Panteleimoni Alexandrou
Date: 09/03/2026 v0.1 

Inputs: None
Outputs: PDO object ($pdo)

Error Messages:
- If connection fails, throws PDOException with detailed error message.

Files in use:
- AppointmentController.php
- All backend modules requiring database access
*/

declare(strict_types=1);

$host = "127.0.0.1";
$db = "advicut";
$user = "root";
$pass = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,         // Enables exception handling
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,    // Fetch results as associative array
    PDO::ATTR_EMULATE_PREPARES => false,                 // Use real prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}