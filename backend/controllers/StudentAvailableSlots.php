<?php
/* 
NAME: Student Available Slots Page
Description: Displays available advisor office hours to the student and allows slot selection for booking.
Author: (βάλε το όνομά σου)
Date: 28/02/2026 v0.2
Inputs: None
Outputs: HTML page showing office hours (available slots) + Select button
Error Messages: Shows database/query error if something fails
Files in use: backend/config/db.php, Bootstrap CSS from the web
*/

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$errorMessage = "";
$slots = [];

try {
    // For now we show all slots. Later we will filter by advisor or by student's assigned advisor.
    $stmt = $pdo->query("SELECT OfficeHour_ID, Advisor_ID, Day_of_Week, Start_Time, End_Time FROM office_hours");
    $slots = $stmt->fetchAll();
} catch (Throwable $e) {
    $errorMessage = $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AdviCut - Available Slots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width:900px;">
            <h3 class="text-center mb-4">Available Advisor Slots</h3>

            <?php if ($errorMessage !== ""): ?>
                <div class="alert alert-danger text-center">
                    Error: <?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>

            <?php if (count($slots) === 0): ?>
                <div class="alert alert-secondary text-center">
                    No available slots found.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>Slot ID</th>
                                <th>Advisor ID</th>
                                <th>Day</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($slots as $s): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string)$s['OfficeHour_ID']) ?></td>
                                    <td><?= htmlspecialchars((string)$s['Advisor_ID']) ?></td>
                                    <td><?= htmlspecialchars((string)$s['Day_of_Week']) ?></td>
                                    <td><?= htmlspecialchars((string)$s['Start_Time']) ?></td>
                                    <td><?= htmlspecialchars((string)$s['End_Time']) ?></td>
                                    <td>
                                        <a href="StudentBookAppointment.php?slot_id=<?= (int)$s['OfficeHour_ID'] ?>" 
                                           class="btn btn-primary btn-sm">
                                            Select
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