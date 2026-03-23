<?php
/* 
NAME: Student Book Appointment Page
Description: Allows a student to select an available weekly office hour slot and submit an appointment request (Pending).
Author: Panteleimoni Alexandrou
Date: 20/03/2026 v1.2

Inputs:
- GET or POST: slot_id
- POST: reason

Outputs:
- HTML booking form
- Success confirmation message

Error Messages:
- Student not found
- No advisor assigned
- Invalid slot
- Reason is required
- Appointment already exists for this slot
- Database/query error if something fails

Files in use:
- backend/config/db.php
- users table
- student_advisors table
- office_hours table
- appointment_history table
- Bootstrap CSS from the web

Notes:
- TEMP: student is hardcoded for testing.
- Later this must come from session/login through User Management.
- Booking is inserted as Pending (Status = 0).
*/

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$errorMessage = "";
$successMessage = "";

/*
------------------------------------------------------------
TEMP TEST DATA
------------------------------------------------------------
*/
$studentExternalId = 27407;

/*
------------------------------------------------------------
GET STUDENT USER_ID
------------------------------------------------------------
*/
$studentId = 0;
$advisorExternalId = 0;
$advisorId = 0;

try {
    $sql = "SELECT User_ID
            FROM users
            WHERE External_ID = :ext
              AND Role = 'Student'
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ext' => $studentExternalId]);
    $student = $stmt->fetch();

    if (!$student) {
        throw new Exception("Student not found.");
    }

    $studentId = (int)$student['User_ID'];

    $sql = "SELECT Advisor_ID
            FROM student_advisors
            WHERE Student_ID = :sid
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['sid' => $studentExternalId]);
    $row = $stmt->fetch();

    if (!$row) {
        throw new Exception("No advisor assigned.");
    }

    $advisorExternalId = (int)$row['Advisor_ID'];

    $sql = "SELECT User_ID
            FROM users
            WHERE External_ID = :ext
              AND Role = 'Advisor'
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ext' => $advisorExternalId]);
    $advisor = $stmt->fetch();

    if (!$advisor) {
        throw new Exception("Advisor user not found.");
    }

    $advisorId = (int)$advisor['User_ID'];

} catch (Throwable $e) {
    $errorMessage = $e->getMessage();
}

/*
------------------------------------------------------------
GET SLOT
Accept slot_id from GET or POST
------------------------------------------------------------
*/
$slotId = 0;

if (isset($_POST['slot_id'])) {
    $slotId = (int)$_POST['slot_id'];
} elseif (isset($_GET['slot_id'])) {
    $slotId = (int)$_GET['slot_id'];
}

$slot = null;

if ($slotId > 0 && $errorMessage === "") {
    try {
        $sql = "SELECT OfficeHour_ID, Advisor_ID, Day_of_Week, Start_Time, End_Time
                FROM office_hours
                WHERE OfficeHour_ID = :id
                  AND Advisor_ID = :advisor";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $slotId,
            'advisor' => $advisorId
        ]);
        $slot = $stmt->fetch();

        if (!$slot) {
            $errorMessage = "Invalid slot.";
        }

    } catch (Throwable $e) {
        $errorMessage = $e->getMessage();
    }
}

/*
------------------------------------------------------------
HANDLE BOOKING
------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reason']) && $slot && $errorMessage === "") {
    $reason = trim($_POST['reason'] ?? '');

    if ($reason === '') {
        $errorMessage = "Reason is required.";
    } else {
        try {
            $appointmentDate = date('Y-m-d') . ' ' . $slot['Start_Time'];

            $checkSql = "SELECT Appointment_ID
                         FROM appointment_history
                         WHERE OfficeHour_ID = :officehour_id
                           AND Status IN (0, 1)
                         LIMIT 1";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([
                'officehour_id' => $slotId
            ]);

            if ($checkStmt->fetch()) {
                $errorMessage = "This slot is no longer available.";
            } else {
                $sql = "INSERT INTO appointment_history
                        (Student_ID, Advisor_ID, OfficeHour_ID, Reason, Appointment_Date, Status, Attendance)
                        VALUES (:sid, :aid, :officehour_id, :reason, :date, 0, 0)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'sid' => $studentId,
                    'aid' => $advisorId,
                    'officehour_id' => $slotId,
                    'reason' => $reason,
                    'date' => $appointmentDate
                ]);

                header("Location: StudentBookAppointment.php?slot_id=$slotId&success=1");
                exit;
            }

        } catch (Throwable $e) {
            $errorMessage = $e->getMessage();
        }
    }
}

/*
------------------------------------------------------------
SUCCESS MESSAGE
------------------------------------------------------------
*/
if (isset($_GET['success'])) {
    $successMessage = "Appointment booked successfully and is now pending.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width:700px;">

        <h3 class="text-center mb-4">Book Appointment</h3>

        <?php if ($errorMessage): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="alert alert-success text-center">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php endif; ?>

        <?php if ($slot): ?>
            <div class="mb-3">
                <strong>Day:</strong> <?= htmlspecialchars($slot['Day_of_Week']) ?><br>
                <strong>Time:</strong> <?= htmlspecialchars($slot['Start_Time']) ?> - <?= htmlspecialchars($slot['End_Time']) ?>
            </div>

            <form method="POST">
                <input type="hidden" name="slot_id" value="<?= (int)$slot['OfficeHour_ID'] ?>">
                <textarea name="reason" class="form-control mb-3" placeholder="Reason..." required></textarea>
                <button class="btn btn-primary w-100">Confirm Booking</button>
            </form>
        <?php endif; ?>

        <div class="mt-3 text-center">
            <a href="StudentAvailableSlots.php" class="btn btn-secondary">Back</a>
        </div>

    </div>
</div>

</body>
</html>