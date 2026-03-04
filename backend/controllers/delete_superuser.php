<?php
/* Name: delete_SuperUser Controller
   Description: the bridge between admin_dashboard and admin class being able to send the information on the html
   and send them to the adminclass
   Paraskevas Vafeiadis
   04-Mar-2026 v0.1
   Inputs: SuperUser id(User id)
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

$userid = intval($_POST['User_ID'] ?? 0);
if ($userid <= 0) {
    header('Location: ../../frontend/admin_dashboard.php?error=invalid_id');
    exit();
}

$success = $admin->deleteSuperUser($userid);
if ($success) {
    header('Location: ../../frontend/admin_dashboard.php?success=SuperUser Deleted Successfully');
} else {
    header('Location: ../../frontend/admin_dashboard.php?error=operation_failed');
}
exit();
