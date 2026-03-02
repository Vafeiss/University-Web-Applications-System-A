<?php
/* 
NAME: Student Book Appointment Page
Description: Allows a student to select an available office hour slot and submit an appointment request (Pending).
Author: Panteleimoni Alexandrou
Date: 28/02/2026 v0.1
Inputs: 
- GET: slot_id
- POST: reason
Outputs: HTML booking form + confirmation message
Error Messages: Shows validation and database errors if something fails
Files in use: backend/config/db.php, Bootstrap CSS from the web
*/

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$errorMessage = "";
$successMessage = "";

// TEMP: hardcoded student for testing. Later this must come from session/login.
$studentId = 27407;

// Get slot_id from URL
$slotId = isset($_GET['slot_id']) ? (int)$_GET['slot_id'] : 0;
$slot = null;

// 1) Load slot details
if ($slotId > 0) {
    try {
        $sql = "SELECT OfficeHour_ID, Advisor_ID, Day_of_Week, Start_Time, End_Time
                FROM office_hours
                WHERE OfficeHour_ID = :slot_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['slot_id' => $slotId]);
        $slot = $stmt->fetch();

        if (!$slot) {
            $errorMessage = "Selected slot was not found.";
        }
    } catch (Throwable $e) {
        $errorMessage = $e->getMessage();
    }
} else {
    $errorMessage = "Invalid slot selection.";
}

// 2) Handle POST (create appointment request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $slot) {
    $reason = trim($_POST['reason'] ?? '');

    if ($reason === '') {
        $errorMessage = "Reason field is required.";
    } else {
        try {
            // Create an Appointment_Date using the next occurrence of the slot day (simple approach)
            // For now we store "today + start time" as a placeholder. Later we can calculate correct date per weekday.
            $appointmentDate = date('Y-m-d') . ' ' . $slot['Start_Time'];

            $sql = "INSERT INTO appointment_history 
                    (Student_ID, Advisor_ID, OfficeHour_ID, Reason, Appointment_Date, Status, Attendance)
                    VALUES (:student_id, :advisor_id, :officehour_id, :reason, :appointment_date, :status, :attendance)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'student_id' => $studentId,
                'advisor_id' => (int)$slot['Advisor_ID'],
                'officehour_id' => (int)$slot['OfficeHour_ID'],
                'reason' => $reason,
                'appointment_date' => $appointmentDate,
                'status' => 0,      // 0 = Pending
                'attendance' => 0   // 0 = Not attended yet
            ]);

            // Redirect to avoid duplicate insert on refresh
            header("Location: StudentBookAppointment.php?slot_id=" . $slotId . "&msg=success");
            exit;

        } catch (Throwable $e) {
            $errorMessage = $e->getMessage();
        }
    }
}

// 3) Success message after redirect
if (isset($_GET['msg']) && $_GET['msg'] === 'success') {
    $successMessage = "Appointment request submitted successfully (Pending).";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AdviCut - Book Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width:700px;">
            <h3 class="text-center mb-4">Book Appointment</h3>

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

            <?php if ($slot): ?>
                <div class="mb-3">
                    <p class="mb-1"><strong>Selected Slot:</strong></p>
                    <ul class="list-group">
                        <li class="list-group-item">Slot ID: <?= htmlspecialchars((string)$slot['OfficeHour_ID']) ?></li>
                        <li class="list-group-item">Advisor ID: <?= htmlspecialchars((string)$slot['Advisor_ID']) ?></li>
                        <li class="list-group-item">Day: <?= htmlspecialchars((string)$slot['Day_of_Week']) ?></li>
                        <li class="list-group-item">Time: <?= htmlspecialchars((string)$slot['Start_Time']) ?> - <?= htmlspecialchars((string)$slot['End_Time']) ?></li>
                    </ul>
                </div>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Reason / Description</label>
                        <textarea name="reason" class="form-control" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Confirm Booking Request</button>
                </form>
            <?php endif; ?>

            <div class="mt-3 text-center">
                <a href="StudentAvailableSlots.php" class="btn btn-secondary">Back to Slots</a>
            </div>
        </div>
    </div>
</body>
</html>