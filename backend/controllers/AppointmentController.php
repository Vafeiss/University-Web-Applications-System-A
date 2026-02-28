<?php
/*NAME: Appointment Controller
Description: Displays appointment records in styled HTML format.
Author: Panteleimoni Alexandrou
Date: 28/02/2026 v0.3
Inputs: None
Outputs: Styled list of appointment records
Error Messages: Displays error message if query fails
Files in use: backend/config/db.php
*/
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 700px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        .empty {
            text-align: center;
            margin-top: 20px;
            color: gray;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Appointment History</h2>

    <?php
    try {
        $stmt = $pdo->query("SELECT * FROM appointment_history");
        $appointments = $stmt->fetchAll();

        if (count($appointments) === 0) {
            echo "<div class='empty'>No appointments found.</div>";
        } else {
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Advisor</th>
                    <th>Date</th>
                    <th>Status</th>
                  </tr>";

            foreach ($appointments as $appointment) {
                echo "<tr>";
                echo "<td>{$appointment['Appointment_ID']}</td>";
                echo "<td>{$appointment['Student_ID']}</td>";
                echo "<td>{$appointment['Advisor_ID']}</td>";
                echo "<td>{$appointment['Appointment_Date']}</td>";
                echo "<td>{$appointment['Status']}</td>";
                echo "</tr>";
            }

            echo "</table>";
        }

    } catch (Throwable $e) {
        echo "<div class='empty'>Error: {$e->getMessage()}</div>";
    }
    ?>
</div>

</body>
</html>