<?php
/* Name: Add_Student Controller 
   Description: the bridge between admin_dashboard and admin class being able to send the information on the html
   and send them to the adminclass / or get the CSV file
   Paraskevas Vafeiadis
   02-Mar-2026 v0.1
   Inputs: Students' informations 
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

// If a CSV file was uploaded use it and call addStudentByCSV
if (isset($_FILES['csv_file']) && is_uploaded_file($_FILES['csv_file']['tmp_name']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['csv_file']['tmp_name'];
    $result = $admin->addStudentByCSV($tmp);
    if ($result === false) {
        header('Location: ../../frontend/admin_dashboard.php?error=csv_invalid');
        exit();
    }

    // build a success message
    $added = intval($result['added'] ?? 0);
    $skipped = intval($result['skipped'] ?? 0);
    header('Location: ../../frontend/admin_dashboard.php?success=' . urlencode("csv_imported_added_{$added}_skipped_{$skipped}"));
    exit();
}

    $external_id = trim($_POST['external_id'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $year       = trim($_POST['year'] ?? '');
    $advisor_id = trim($_POST['advisors_id'] ?? '');
    $advisor_id_int = intval($advisor_id);

// validate required fields
if ($first_name === '' || $last_name === '' || $email === '' || $advisor_id_int <= 0 || $year === '') {
    header('Location: ../../frontend/admin_dashboard.php?error=empty_fields');
    exit();
}

$success = $admin->addStudent($external_id, $first_name, $last_name, $email, $year, $advisor_id_int);
if ($success) {
    header('Location: ../../frontend/admin_dashboard.php?success=Student added Successfully');
} else {
    header('Location: ../../frontend/admin_dashboard.php?error=Something Went Wrong!');
}
exit();
