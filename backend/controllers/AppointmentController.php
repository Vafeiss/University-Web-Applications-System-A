<?php
/*
Name: AppointmentController
Description: Handles appointment related actions between frontend and AppointmentClass
Author: Panteleimoni Alexandrou
Date: 11-Mar-2026 v1.0
*/

declare(strict_types=1);

require_once __DIR__ . '/../modules/AppointmentClass.php';

class AppointmentController {

    public function bookAppointment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../../frontend/index.php");
            exit();
        }

        $studentId = $_SESSION['UserID'];
        $advisorId = $_POST['advisor_id'] ?? null;
        $reason = $_POST['reason'] ?? "";
        $date = $_POST['date'] ?? "";

        $appointment = new AppointmentClass();
        $appointment->createAppointment($studentId, $advisorId, $reason, $date);
    }

    public function approveAppointment()
    {
        $appointmentId = $_POST['appointment_id'] ?? null;

        $appointment = new AppointmentClass();
        $appointment->approveAppointment($appointmentId);
    }

    public function declineAppointment()
    {
        $appointmentId = $_POST['appointment_id'] ?? null;

        $appointment = new AppointmentClass();
        $appointment->declineAppointment($appointmentId);
    }

}