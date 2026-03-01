<?php
/* Name: delete_Advisor Controller 
   Description: the bridge between admin_dashboard and admin class being able to send the information on the html
   and send them to the adminclass
   Paraskevas Vafeiadis
   01-Mar-2026 v0.1
   Inputs: Advisor id 
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

$advisorId = intval($_POST['advisor_id'] ?? 0);
if ($advisorId <= 0) {
    header('Location: ../../frontend/admin_dashboard.php?error=invalid_id');
    exit();
}

$success = $admin->deleteAdvisor($advisorId);
if ($success) {
    header('Location: ../../frontend/admin_dashboard.php?success=advisor_deleted');
} else {
    header('Location: ../../frontend/admin_dashboard.php?error=operation_failed');
}
exit();
