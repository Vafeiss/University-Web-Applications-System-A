<?php
/* 
NAME: Main Dashboard
Description: Displays the main frontend dashboard and provides navigation to the appointment dashboards.
Author: Panteleimoni Alexandrou
Date: 23/03/2026 v1.3

Inputs: None

Outputs:
- HTML main dashboard page
- Navigation buttons for student and advisor appointment dashboards

Error Messages:
- None

Files in use:
- frontend/StudentAppointmentDashboard.php
- frontend/AdvisorAppointmentDashboard.php
- Bootstrap CSS from the web

Notes:
- This version is used as a common home page.
- Later it can be changed to automatic role-based redirect after final merge with login.
*/
declare(strict_types=1);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AdviCut - Main Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="card shadow p-5 rounded-4 text-center">

                    <h1 class="mb-4">AdviCut System</h1>
                    <p class="mb-4">Main Dashboard</p>

                    <div class="d-grid gap-3">
                        <a href="StudentAppointmentDashboard.php" class="btn btn-success btn-lg">
                            Student Appointment Dashboard
                        </a>

                        <a href="AdvisorAppointmentDashboard.php" class="btn btn-primary btn-lg">
                            Advisor Appointment Dashboard
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>