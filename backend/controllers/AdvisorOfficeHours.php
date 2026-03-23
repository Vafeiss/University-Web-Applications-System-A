<?php
/* 
NAME: Advisor Office Hours Management Page
Description: Allows an advisor to view, add, and delete available office hours (fixed weekly slots) using Bootstrap styling.
Author: Panteleimoni Alexandrou
Date: 17/03/2026 v0.3
Inputs: 
- POST: day, start_time, end_time (add slot)
- GET: delete (OfficeHour_ID) (delete slot)
Outputs: HTML page showing advisor office hours + form to add new slot + delete actions
Error Messages: Shows database/query/validation error if something fails
Files in use: backend/config/db.php, office_hours table, users table, Bootstrap CSS from the web
*/

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$errorMessage = "";
$successMessage = "";

/*
TEMP: hardcoded advisor for testing.
Later this must come from session/login or User Management integration.
IMPORTANT:
office_hours.Advisor_ID references users.User_ID
*/
$advisorId = 2;

/*
------------------------------------------------------------
DELETE SLOT (GET ?delete=ID)
------------------------------------------------------------
*/
if (isset($_GET['delete'])) {
    $deleteId = (int)($_GET['delete']);

    if ($deleteId > 0) {
        try {
            $sql = "DELETE FROM office_hours 
                    WHERE OfficeHour_ID = :id 
                    AND Advisor_ID = :advisor_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id' => $deleteId,
                'advisor_id' => $advisorId
            ]);

            header("Location: AdvisorOfficeHours.php?msg=deleted");
            exit;

        } catch (Throwable $e) {
            $errorMessage = $e->getMessage();
        }
    } else {
        $errorMessage = "Invalid slot ID.";
    }
}

/*
------------------------------------------------------------
ADD SLOT (POST)
------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = trim($_POST['day'] ?? '');
    $start = trim($_POST['start_time'] ?? '');
    $end = trim($_POST['end_time'] ?? '');

    if ($day === '' || $start === '' || $end === '') {
        $errorMessage = "All fields are required.";
    } elseif ($start >= $end) {
        $errorMessage = "End time must be later than start time.";
    } else {
        try {
            $checkSql = "SELECT OfficeHour_ID
                         FROM office_hours
                         WHERE Advisor_ID = :advisor_id
                         AND Day_of_Week = :day
                         AND Start_Time = :start_time
                         AND End_Time = :end_time
                         LIMIT 1";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([
                'advisor_id' => $advisorId,
                'day' => $day,
                'start_time' => $start,
                'end_time' => $end
            ]);

            if ($checkStmt->fetch()) {
                $errorMessage = "This office hour slot already exists.";
            } else {
                $sql = "INSERT INTO office_hours (Advisor_ID, Day_of_Week, Start_Time, End_Time)
                        VALUES (:advisor_id, :day, :start_time, :end_time)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'advisor_id' => $advisorId,
                    'day' => $day,
                    'start_time' => $start,
                    'end_time' => $end
                ]);

                header("Location: AdvisorOfficeHours.php?msg=added");
                exit;
            }

        } catch (Throwable $e) {
            $errorMessage = $e->getMessage();
        }
    }
}

/*
------------------------------------------------------------
SUCCESS MESSAGE (after redirect)
------------------------------------------------------------
*/
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added') {
        $successMessage = "Office hour slot added successfully!";
    } elseif ($_GET['msg'] === 'deleted') {
        $successMessage = "Office hour slot deleted successfully!";
    }
}

/*
------------------------------------------------------------
FETCH ADVISOR NAME
------------------------------------------------------------
*/
$advisorName = "Advisor Name";

try {
    $nameSql = "SELECT First_name, Last_Name
                FROM users
                WHERE User_ID = :advisor_id
                AND Role = 'Advisor'
                LIMIT 1";
    $nameStmt = $pdo->prepare($nameSql);
    $nameStmt->execute([
        'advisor_id' => $advisorId
    ]);

    $advisor = $nameStmt->fetch();

    if ($advisor) {
        $advisorName = trim($advisor['First_name'] . ' ' . $advisor['Last_Name']);
    }
} catch (Throwable $e) {
    if ($errorMessage === "") {
        $errorMessage = $e->getMessage();
    }
}

/*
------------------------------------------------------------
FETCH ADVISOR SLOTS
------------------------------------------------------------
*/
$slots = [];
try {
    $sql = "SELECT OfficeHour_ID, Day_of_Week, Start_Time, End_Time
            FROM office_hours
            WHERE Advisor_ID = :advisor_id
            ORDER BY 
                FIELD(Day_of_Week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
                Start_Time ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['advisor_id' => $advisorId]);
    $slots = $stmt->fetchAll();
} catch (Throwable $e) {
    $errorMessage = $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AdviCut - Advisor Office Hours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card shadow p-4 rounded-4">
                    
                    <h3 class="text-center mb-3">Advisor Office Hours</h3>

                    <div class="mb-4">
                        <h5 class="mb-0"><?= htmlspecialchars($advisorName) ?></h5>
                    </div>

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

                    <!-- Add slot form -->
                    <form method="POST" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Day of Week</label>
                            <select name="day" class="form-select" required>
                                <option value="">Select Day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Add Slot</button>
                        </div>
                    </form>

                    <!-- Existing slots -->
                    <?php if (count($slots) === 0): ?>
                        <div class="alert alert-secondary text-center">
                            No office hours found for this advisor.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Slot ID</th>
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
                                            <td><?= htmlspecialchars((string)$s['Day_of_Week']) ?></td>
                                            <td><?= htmlspecialchars((string)$s['Start_Time']) ?></td>
                                            <td><?= htmlspecialchars((string)$s['End_Time']) ?></td>
                                            <td>
                                                <a href="?delete=<?= (int)$s['OfficeHour_ID'] ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Are you sure you want to delete this slot?');">
                                                    Delete
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
        </div>
    </div>
</body>
</html>