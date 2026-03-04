<?php
/* Name: Add_SuperUser Controller
   Description: the bridge between admin_dashboard and admin class being able to send the information on the html
   and send them to the adminclass
   Paraskevas Vafeiadis
   04-Mar-2026 v0.1
   Inputs: SuperUser Email
   Outputs: None
   Filesi n use: AdminClass.php / admin_dashboard.php
*/

declare(strict_types=1);

session_start();
require_once __DIR__ . '/../modules/AdminClass.php';

$admin = new Admin();
$admin->Check_Session('Admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../frontend/admin_dashboard.php');
    exit();
}

$email      = trim($_POST['email'] ?? '');


if ($email === '') {
    header('Location: ../../frontend/admin_dashboard.php?error=Please Insert An Email');
    exit();
}

$success = $admin->addSuperUser($email);
if ($success) {
    header('Location: ../../frontend/admin_dashboard.php?success=SuperUser Added Succesfully');
} else {
    header('Location: ../../frontend/admin_dashboard.php?error=Something Went Wrong');
}
exit();