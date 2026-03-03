<?php
/* Name: delete_student Controller
   Description: the bridge between admin_dashboard and admin class being able to send the information on the html
   and send them to the adminclass
   Paraskevas Vafeiadis
   03-Mar-2026 v0.1
   Inputs: Student ID
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

$student_id = intval($_POST['student_ID'] ?? 0);
if ($student_id <= 0) {
    header('Location: ../../frontend/admin_dashboard.php?error=invalid_id');
    exit();
}

$success = $admin->deleteStudent($student_id);
if ($success) {
    header('Location: ../../frontend/admin_dashboard.php?success=advisor_deleted');
} else {
    header('Location: ../../frontend/admin_dashboard.php?error=operation_failed');
}
exit();
