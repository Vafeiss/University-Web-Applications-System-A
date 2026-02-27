<?php
/*Name: LogoutController.php
Description: This controller is responsible for handling the logout process of users.
Paraskevas Vafeiadis
27-feb-2026 v0.1
Inputs: None
Outputs: None
Error Messages: None
Files in use: UsersClass.php where the log_out method is.
*/
declare(strict_types=1);
require_once __DIR__ . '/../modules/UsersClass.php';
$user = new Users();
$user->Log_out();
?>