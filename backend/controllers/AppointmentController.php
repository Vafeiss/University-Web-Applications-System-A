<?php
/* 
NAME: Appointment History Page
Description: Displays appointment history records using Bootstrap styling (similar to login page).
Author: Panteleimoni Alexandrou
Date: 28/2/2026 v0.4
Inputs: None
Outputs: HTML page showing appointment history
Error Messages: Displays database/query error if something fails
Files in use: backend/config/db.php, Bootstrap CSS from the web
*/

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$errorMessage = "";

try {
    $stmt = $pdo->query("SELECT * FROM appointment_history");
    $appointments = $stmt->fetchAll();
} catch (Throwable $e) {
    $errorMessage = $e->getMessage();
    $appointments = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AdviCut - Appointment History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width:900px;">
            <h3 class="text-center mb-4">Appointment History</h3>

            <?php if ($errorMessage !== ""): ?>
                <div class="alert alert-danger text-center">
                    Error: <?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>

            <?php if (count($appointments) === 0): ?>
                <div class="alert alert-secondary text-center">
                    No appointments found.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>Appointment ID</th>
                                <th>Student ID</th>
                                <th>Advisor ID</th>
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
                                    <td><?= htmlspecialchars((string)$a['Student_ID']) ?></td>
                                    <td><?= htmlspecialchars((string)$a['Advisor_ID']) ?></td>
                                    <td><?= htmlspecialchars((string)$a['Reason']) ?></td>
                                    <td><?= htmlspecialchars((string)$a['Appointment_Date']) ?></td>
                                    <td><?= htmlspecialchars((string)$a['Status']) ?></td>
                                    <td><?= htmlspecialchars((string)$a['Attendance']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="mt-3 text-center">
                <a href="../../frontend/index.php" class="btn btn-primary">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>