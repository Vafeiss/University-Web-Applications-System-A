<?php
/*Name: Authentication Controller
Description: This controller is responsible for handling the authentication process of users.
Paraskevas Vafeiadis
24-feb-2026 v0.1
Inputs: Email,Password
Outputs: Object of users
Error Messages : If the request method is not post it will redirect to the login page.
Files in use: UsersClass.php where the class is defined and the log_in method is called
index.php where the form is and where it will get the data email and password from.
*/
declare(strict_types=1);

require_once __DIR__ . '/../modules/UsersClass.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit();
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$user = new Users();
$user->log_in($email, $password);

