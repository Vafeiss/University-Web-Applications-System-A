<?php
/* 
NAME: Student Appointment Dashboard
Description: Displays the student appointment dashboard and provides navigation to student appointment management functions.
Author: Panteleimoni Alexandrou
Date: 23/03/2026 v1.3

Inputs: None

Outputs:
- HTML dashboard page for student appointment functions
- Navigation buttons for available slots and appointment history

Error Messages:
- None

Files in use:
- backend/controllers/StudentAvailableSlots.php
- backend/controllers/StudentAppointmentHistory.php
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
    <title>AdviCut - Student Appointment Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">
                <div class="card shadow p-4 rounded-4">
                    
                    <h2 class="text-center mb-4">Student Appointment Dashboard</h2>

                    <div class="d-grid gap-3">
                        <a href="../backend/controllers/StudentAvailableSlots.php" class="btn btn-success btn-lg">
                            View Available Slots
                        </a>

                        <a href="../backend/controllers/StudentAppointmentHistory.php" class="btn btn-success btn-lg">
                            View Appointment History
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