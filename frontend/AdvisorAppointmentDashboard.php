<?php
/* 
NAME: Advisor Appointment Dashboard
Description: Displays the advisor appointment dashboard and provides navigation to advisor appointment management functions.
Author: Panteleimoni Alexandrou
Date: 23/03/2026 v1.3

Inputs: None

Outputs:
- HTML dashboard page for advisor appointment functions
- Navigation buttons for office hours, appointment requests and attendance

Error Messages:
- None

Files in use:
- backend/controllers/AdvisorOfficeHours.php
- backend/controllers/AdvisorAppointmentRequests.php
- backend/controllers/AdvisorAttendance.php
- frontend/index.php
- Bootstrap CSS from the web

Notes:
- This version does not use session role checks.
- It is intended for testing and frontend navigation before final merge with login.
*/
declare(strict_types=1);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AdviCut - Advisor Appointment Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">
                <div class="card shadow p-4 rounded-4">
                    
                    <h2 class="text-center mb-4">Advisor Appointment Dashboard</h2>

                    <div class="d-grid gap-3">
                        <a href="../backend/controllers/AdvisorOfficeHours.php" class="btn btn-primary btn-lg">
                            Manage Office Hours
                        </a>

                        <a href="../backend/controllers/AdvisorAppointmentRequests.php" class="btn btn-primary btn-lg">
                            View Appointment Requests
                        </a>

                        <a href="../backend/controllers/AdvisorAttendance.php" class="btn btn-primary btn-lg">
                            Mark Attendance
                        </a>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="index.php" class="btn btn-secondary">
                            Back to Home
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>