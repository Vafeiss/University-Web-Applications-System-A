<?php
/* 
NAME: Student Appointment History Page
Description: Displays the student's appointment requests and their current status.
Author: Panteleimoni Alexandrou
Date: 23/03/2026 v1.0
Inputs: None
Outputs: HTML page showing student appointment history
Error Messages: Shows database/query error if something fails
Files in use: backend/config/db.php, users table, appointment_history table, Bootstrap CSS from the web
*/

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$errorMessage = "";
$appointments = [];

/*
TEMP: hardcoded student for testing
Later this must come from session/login
*/
$studentId = 4;

/*
------------------------------------------------------------
STATUS LABEL
------------------------------------------------------------
*/
function getStatusLabel(int $status): string
{
    switch ($status) {
        case 0:
            return 'Pending';
        case 1:
            return 'Approved';
        case 2:
            return 'Declined';
        case 3:
            return 'Cancelled';
        case 4:
            return 'Completed';
        default:
            return 'Unknown';
    }
}

/*
------------------------------------------------------------
STATUS BADGE CLASS
------------------------------------------------------------
*/
function getStatusBadgeClass(int $status): string
{
    switch ($status) {
        case 0:
            return 'bg-warning text-dark';
        case 1:
            return 'bg-success';
        case 2:
            return 'bg-danger';
        case 3:
            return 'bg-secondary';
        case 4:
            return 'bg-primary';
        default:
            return 'bg-dark';
    }
}

/*
------------------------------------------------------------
FETCH STUDENT APPOINTMENT HISTORY
------------------------------------------------------------
*/
try {
    $sql = "SELECT 
                ah.Appointment_ID,
                ah.OfficeHour_ID,
                ah.Reason,
                ah.Appointment_Date,
                ah.Status,
                ah.Attendance,
                u.First_name,
                u.Last_Name
            FROM appointment_history ah
            LEFT JOIN users u
                ON ah.Advisor_ID = u.User_ID
            WHERE ah.Student_ID = :student_id
            ORDER BY ah.Appointment_Date DESC, ah.Appointment_ID DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'student_id' => $studentId
    ]);

    $appointments = $stmt->fetchAll();

} catch (Throwable $e) {
    $errorMessage = $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AdviCut - My Appointment History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card shadow p-4 rounded-4">

                    <h3 class="text-center mb-4">My Appointment History</h3>

                    <?php if ($errorMessage !== ""): ?>
                        <div class="alert alert-danger text-center">
                            Error: <?= htmlspecialchars($errorMessage) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($errorMessage === "" && count($appointments) === 0): ?>
                        <div class="alert alert-secondary text-center">
                            No appointments found.
                        </div>
                    <?php endif; ?>

                    <?php if (count($appointments) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Appointment ID</th>
                                        <th>Advisor</th>
                                        <th>Slot ID</th>
                                        <th>Reason</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $a): ?>
                                        <tr>
                                            <td><?= htmlspecialchars((string)$a['Appointment_ID']) ?></td>
                                            <td><?= htmlspecialchars(trim((string)($a['First_name'] ?? '') . ' ' . (string)($a['Last_Name'] ?? ''))) ?></td>
                                            <td><?= htmlspecialchars((string)($a['OfficeHour_ID'] ?? '')) ?></td>
                                            <td class="text-start"><?= htmlspecialchars((string)$a['Reason']) ?></td>
                                            <td><?= htmlspecialchars((string)$a['Appointment_Date']) ?></td>
                                            <td>
                                                <span class="badge <?= htmlspecialchars(getStatusBadgeClass((int)$a['Status'])) ?> px-3 py-2">
                                                    <?= htmlspecialchars(getStatusLabel((int)$a['Status'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                if ((int)$a['Attendance'] === 1) {
                                                    echo 'Present';
                                                } elseif ((int)$a['Attendance'] === 0) {
                                                    echo 'Not marked';
                                                } else {
                                                    echo 'Absent';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <div class="mt-3 text-center">
                        <a href="../../frontend/index.php" class="btn btn-primary">Back</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>