<?php
/* 
NAME: Advisor Appointment Requests Page
Description: Displays pending appointment requests for an advisor and allows approval or rejection.
Author: Panteleimoni Alexandrou
Date: 02/03/2026 v0.1
Inputs:
- GET: action (approve/decline), id (Appointment_ID)
Outputs: HTML page showing pending requests + action buttons
Error Messages: Shows database/query error if something fails
Files in use: backend/config/db.php, Bootstrap CSS from the web
*/

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$errorMessage = "";
$successMessage = "";

// TEMP: hardcoded advisor for testing. Later this must come from session/login.
$advisorId = 30000;

// Handle approve/decline action
if (isset($_GET['action'], $_GET['id'])) {
    $action = (string)$_GET['action'];
    $appointmentId = (int)$_GET['id'];

    if ($appointmentId > 0 && ($action === 'approve' || $action === 'decline')) {
        $newStatus = ($action === 'approve') ? 1 : 2; // 0=pending, 1=approved, 2=declined

        try {
            $sql = "UPDATE appointment_history
                    SET Status = :status
                    WHERE Appointment_ID = :appointment_id
                    AND Advisor_ID = :advisor_id
                    AND Status = 0";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'status' => $newStatus,
                'appointment_id' => $appointmentId,
                'advisor_id' => $advisorId
            ]);

            if ($stmt->rowCount() > 0) {
                $successMessage = ($action === 'approve')
                    ? "Appointment request approved successfully!"
                    : "Appointment request declined successfully!";
            } else {
                $errorMessage = "No pending appointment found to update (or not authorized).";
            }

        } catch (Throwable $e) {
            $errorMessage = $e->getMessage();
        }
    } else {
        $errorMessage = "Invalid action or appointment id.";
    }
}

// Fetch pending requests for this advisor (Status = 0)
$requests = [];
try {
    $sql = "SELECT 
                ah.Appointment_ID,
                ah.Student_ID,
                ah.Reason,
                ah.Appointment_Date,
                ah.Status,
                oh.Day_of_Week,
                oh.Start_Time,
                oh.End_Time
            FROM appointment_history ah
            LEFT JOIN office_hours oh ON ah.OfficeHour_ID = oh.OfficeHour_ID
            WHERE ah.Advisor_ID = :advisor_id
            AND ah.Status = 0
            ORDER BY ah.Appointment_ID DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['advisor_id' => $advisorId]);
    $requests = $stmt->fetchAll();

} catch (Throwable $e) {
    $errorMessage = $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AdviCut - Pending Appointment Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width:1000px;">
            <h3 class="text-center mb-4">Pending Appointment Requests</h3>

            <?php if ($errorMessage !== ""): ?>
                <div class="alert alert-danger text-center">
                    Error: <?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>

            <?php if ($successMessage !== ""): ?>
                <div class="alert alert-success text-center">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php endif; ?>

            <?php if (count($requests) === 0): ?>
                <div class="alert alert-secondary text-center">
                    No pending appointment requests found.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>Appointment ID</th>
                                <th>Student ID</th>
                                <th>Reason</th>
                                <th>Slot (Day)</th>
                                <th>Slot (Time)</th>
                                <th>Requested Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string)$r['Appointment_ID']) ?></td>
                                    <td><?= htmlspecialchars((string)$r['Student_ID']) ?></td>
                                    <td><?= htmlspecialchars((string)$r['Reason']) ?></td>
                                    <td><?= htmlspecialchars((string)($r['Day_of_Week'] ?? 'N/A')) ?></td>
                                    <td>
                                        <?= htmlspecialchars((string)($r['Start_Time'] ?? 'N/A')) ?>
                                        -
                                        <?= htmlspecialchars((string)($r['End_Time'] ?? 'N/A')) ?>
                                    </td>
                                    <td><?= htmlspecialchars((string)$r['Appointment_Date']) ?></td>
                                    <td>
                                        <a class="btn btn-success btn-sm"
                                           href="?action=approve&id=<?= (int)$r['Appointment_ID'] ?>"
                                           onclick="return confirm('Approve this appointment request?');">
                                            Approve
                                        </a>
                                        <a class="btn btn-danger btn-sm"
                                           href="?action=decline&id=<?= (int)$r['Appointment_ID'] ?>"
                                           onclick="return confirm('Decline this appointment request?');">
                                            Decline
                                        </a>
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
</body>
</html>