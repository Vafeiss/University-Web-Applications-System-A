<?php
/* Name: Add_Advisor Controller 
   Description: the bridge between admin_dashboard and admin class being able to send the information on the html
   and send them to the adminclass / or get the CSV file
   Paraskevas Vafeiadis
   01-Mar-2026 v0.1
   Inputs: Advisors' informations 
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

// If a CSV file was uploaded use it and call addAdvisorByCSV
if (isset($_FILES['csv_file']) && is_uploaded_file($_FILES['csv_file']['tmp_name']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['csv_file']['tmp_name'];
    $result = $admin->addAdvisorByCSV($tmp);
    if ($result === false) {
        header('Location: ../../frontend/admin_dashboard.php?error=csv_invalid');
        exit();
    }

    // build a concise success message
    $added = intval($result['added'] ?? 0);
    $skipped = intval($result['skipped'] ?? 0);
    header('Location: ../../frontend/admin_dashboard.php?success=' . urlencode("csv_imported_added_{$added}_skipped_{$skipped}"));
    exit();
}

$external_id = trim($_POST['external_id'] ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$department = trim($_POST['department'] ?? '');

if ($first_name === '' || $last_name === '' || $email === '' || $department === '') {
    header('Location: ../../frontend/admin_dashboard.php?error=empty_fields');
    exit();
}

$ok = $admin->addAdvisor($external_id, $first_name, $last_name, $email, $phone, $department);
if ($ok) {
    header('Location: ../../frontend/admin_dashboard.php?success=advisor_added');
} else {
    header('Location: ../../frontend/admin_dashboard.php?error=operation_failed');
}
exit();
