<?php
/* 
NAME: Advisor Appointment Requests Page
Description: Displays pending appointment requests for an advisor and allows approval or rejection.
Author: Panteleimoni Alexandrou
Date: 23/03/2026 v1.1

Inputs:
- GET: action (approve / decline)
- GET: id (Appointment_ID)

Outputs:
- HTML page showing pending appointment requests
- Success or error message after an action

Error Messages:
- Invalid action or appointment id
- No pending appointment found
- Database/query error if something fails

Files in use:
- backend/config/db.php
- users table
- appointment_history table
- Bootstrap CSS from the web
*/

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$errorMessage = "";
$successMessage = "";
$advisorName = "Advisor Name";

/*
TEMP: hardcoded advisor User_ID for testing
*/
$advisorId = 2;

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
GET ADVISOR NAME
------------------------------------------------------------
*/
function getAdvisorName(PDO $pdo, int $advisorId): string
{
    $sql = "SELECT First_name, Last_Name
            FROM users
            WHERE User_ID = :advisor_id
              AND Role = 'Advisor'
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'advisor_id' => $advisorId
    ]);

    $advisor = $stmt->fetch();

    if ($advisor) {
        return trim($advisor['First_name'] . ' ' . $advisor['Last_Name']);
    }

    return 'Advisor Name';
}

/*
------------------------------------------------------------
UPDATE STATUS
Only Pending requests can be approved/declined
------------------------------------------------------------
*/
function updateAppointmentStatus(PDO $pdo, int $appointmentId, int $advisorId, int $newStatus): bool
{
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

    return $stmt->rowCount() > 0;
}

/*
------------------------------------------------------------
HANDLE ACTION FIRST + REDIRECT
------------------------------------------------------------
*/
if (isset($_GET['action'], $_GET['id'])) {
    $action = (string)$_GET['action'];
    $appointmentId = (int)$_GET['id'];

    if ($appointmentId <= 0 || !in_array($action, ['approve', 'decline'], true)) {
        header("Location: AdvisorAppointmentRequests.php?error=invalid");
        exit;
    }

    $newStatus = ($action === 'approve') ? 1 : 2;

    try {
        $updated = updateAppointmentStatus($pdo, $appointmentId, $advisorId, $newStatus);

        if ($updated) {
            $msg = ($action === 'approve') ? 'approved' : 'declined';
            header("Location: AdvisorAppointmentRequests.php?msg=" . $msg);
            exit;
        } else {
            header("Location: AdvisorAppointmentRequests.php?error=notfound");
            exit;
        }
    } catch (Throwable $e) {
        header("Location: AdvisorAppointmentRequests.php?error=dberror");
        exit;
    }
}

/*
------------------------------------------------------------
MESSAGES AFTER REDIRECT
------------------------------------------------------------
*/
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'approved') {
        $successMessage = "Appointment request approved successfully.";
    } elseif ($_GET['msg'] === 'declined') {
        $successMessage = "Appointment request declined successfully.";
    }
}

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'invalid') {
        $errorMessage = "Invalid action or appointment ID.";
    } elseif ($_GET['error'] === 'notfound') {
        $errorMessage = "No pending appointment found for this advisor.";
    } elseif ($_GET['error'] === 'dberror') {
        $errorMessage = "Database error while updating appointment request.";
    }
}

/*
------------------------------------------------------------
GET ADVISOR NAME
------------------------------------------------------------
*/
try {
    $advisorName = getAdvisorName($pdo, $advisorId);
} catch (Throwable $e) {
    $advisorName = "Advisor Name";
}

/*
------------------------------------------------------------
FETCH PENDING REQUESTS
------------------------------------------------------------
*/
$requests = [];

try {
    $sql = "SELECT 
                ah.Appointment_ID,
                ah.Student_ID,
                ah.OfficeHour_ID,
                ah.Reason,
                ah.Appointment_Date,
                ah.Status,
                u.First_name,
                u.Last_Name
            FROM appointment_history ah
            LEFT JOIN users u
                ON ah.Student_ID = u.User_ID
            WHERE ah.Advisor_ID = :advisor_id
              AND ah.Status = 0
            ORDER BY ah.Appointment_Date ASC, ah.Appointment_ID DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'advisor_id' => $advisorId
    ]);

    $requests = $stmt->fetchAll();

} catch (Throwable $e) {
    $errorMessage = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdviCut - Pending Appointment Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">
                            <h2 class="fw-bold mb-3">Pending Appointment Requests</h2>
                            <div class="text-start">
                                <h5 class="mb-0"><?= htmlspecialchars($advisorName) ?></h5>
                            </div>
                        </div>

                        <?php if ($errorMessage !== ""): ?>
                            <div class="alert alert-danger text-center rounded-3">
                                <strong>Error:</strong> <?= htmlspecialchars($errorMessage) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($successMessage !== ""): ?>
                            <div class="alert alert-success text-center rounded-3">
                                <?= htmlspecialchars($successMessage) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (count($requests) === 0): ?>
                            <div class="alert alert-secondary text-center rounded-3 mb-0">
                                No pending appointment requests.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle text-center mb-0">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="py-3">Appointment ID</th>
                                            <th class="py-3">Student</th>
                                            <th class="py-3">Student ID</th>
                                            <th class="py-3">Slot ID</th>
                                            <th class="py-3">Reason</th>
                                            <th class="py-3">Requested Date</th>
                                            <th class="py-3">Status</th>
                                            <th class="py-3" style="min-width: 180px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($requests as $request): ?>
                                            <tr>
                                                <td><?= htmlspecialchars((string)$request['Appointment_ID']) ?></td>
                                                <td><?= htmlspecialchars(trim((string)($request['First_name'] ?? '') . ' ' . (string)($request['Last_Name'] ?? ''))) ?></td>
                                                <td><?= htmlspecialchars((string)$request['Student_ID']) ?></td>
                                                <td><?= htmlspecialchars((string)($request['OfficeHour_ID'] ?? '')) ?></td>
                                                <td class="text-start px-3"><?= htmlspecialchars((string)$request['Reason']) ?></td>
                                                <td><?= htmlspecialchars((string)$request['Appointment_Date']) ?></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark px-3 py-2">
                                                        <?= htmlspecialchars(getStatusLabel((int)$request['Status'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                                        <a href="AdvisorAppointmentRequests.php?action=approve&id=<?= (int)$request['Appointment_ID'] ?>"
                                                           class="btn btn-success btn-sm px-3"
                                                           onclick="return confirm('Approve this appointment request?');">
                                                            Approve
                                                        </a>

                                                        <a href="AdvisorAppointmentRequests.php?action=decline&id=<?= (int)$request['Appointment_ID'] ?>"
                                                           class="btn btn-danger btn-sm px-3"
                                                           onclick="return confirm('Decline this appointment request?');">
                                                            Decline
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                        <div class="mt-4 text-center">
                            <a href="../../frontend/index.php" class="btn btn-primary px-4">
                                Back
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>