<?php
/*
Name: AppointmentClass
Description: Handles database logic for appointments
Author: Panteleimoni Alexandrou
Date: 11-Mar-2026 v1.0
*/

class AppointmentClass {

    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost","root","","advicut");

        if ($this->conn->connect_error) {
            die("Database connection failed");
        }
    }

    public function createAppointment($studentId,$advisorId,$reason,$date)
    {
        $status = 0;
        $attendance = 0;

        $stmt = $this->conn->prepare(
        "INSERT INTO appointment_history
        (Student_ID, Advisor_ID, Reason, Appointment_Date, Status, Attendance)
        VALUES (?,?,?,?,?,?)");

        $stmt->bind_param("iissii",
            $studentId,
            $advisorId,
            $reason,
            $date,
            $status,
            $attendance
        );

        $stmt->execute();

        header("Location: ../../frontend/student_dashboard.php?success=appointment_created");
        exit();
    }

    public function approveAppointment($appointmentId)
    {
        $stmt = $this->conn->prepare(
        "UPDATE appointment_history SET Status = 1 WHERE Appointment_ID = ?");

        $stmt->bind_param("i",$appointmentId);
        $stmt->execute();

        header("Location: ../../frontend/advisor_dashboard.php?success=approved");
        exit();
    }

    public function declineAppointment($appointmentId)
    {
        $stmt = $this->conn->prepare(
        "UPDATE appointment_history SET Status = 2 WHERE Appointment_ID = ?");

        $stmt->bind_param("i",$appointmentId);
        $stmt->execute();

        header("Location: ../../frontend/advisor_dashboard.php?success=declined");
        exit();
    }

}