<?php

session_start();

require_once __DIR__ . '/../modules/AdminClass.php';

class AdminController
{
    private AdminClass $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminClass();
    }

    public function handleRequest(): void
    {
        $action = $_GET['action'] ?? $_POST['action'] ?? 'dashboard';

        switch ($action) {
            case 'dashboard':
                $this->dashboard();
                break;

            case 'students':
                $this->students();
                break;

            case 'advisors':
                $this->advisors();
                break;

            case 'assignments':
                $this->assignments();
                break;

            case 'system_data':
                $this->systemData();
                break;

            case 'assign_advisor':
                $this->assignAdvisor();
                break;

            case 'remove_advisor':
                $this->removeAdvisor();
                break;

            case 'search_students':
                $this->searchStudents();
                break;

            case 'search_advisors':
                $this->searchAdvisors();
                break;

            default:
                $this->dashboard();
                break;
        }
    }

    private function dashboard(): void
    {
        $stats = $this->adminModel->getDashboardStats();

        $_SESSION['admin_dashboard_stats'] = $stats;

        header('Location: ../../frontend/AdminDashboard.php');
        exit();
    }

    private function students(): void
    {
        $students = $this->adminModel->getAllStudents();

        $_SESSION['admin_students'] = $students;

        header('Location: ../../frontend/ManageStudents.php');
        exit();
    }

    private function advisors(): void
    {
        $advisors = $this->adminModel->getAllAdvisors();

        $_SESSION['admin_advisors'] = $advisors;

        header('Location: ../../frontend/ManageAdvisors.php');
        exit();
    }

    private function assignments(): void
    {
        $studentsWithAdvisors = $this->adminModel->getStudentsWithAdvisors();
        $unassignedStudents = $this->adminModel->getUnassignedStudents();
        $advisors = $this->adminModel->getAllAdvisors();

        $_SESSION['admin_students_with_advisors'] = $studentsWithAdvisors;
        $_SESSION['admin_unassigned_students'] = $unassignedStudents;
        $_SESSION['admin_advisors_for_assignment'] = $advisors;

        header('Location: ../../frontend/AssignStudentsToAdvisors.php');
        exit();
    }

    private function systemData(): void
    {
        $stats = $this->adminModel->getDashboardStats();
        $appointmentOverview = $this->adminModel->getAppointmentOverview();
        $studentsWithAdvisors = $this->adminModel->getStudentsWithAdvisors();

        $_SESSION['admin_system_stats'] = $stats;
        $_SESSION['admin_appointment_overview'] = $appointmentOverview;
        $_SESSION['admin_students_with_advisors'] = $studentsWithAdvisors;

        header('Location: ../../frontend/ViewSystemData.php');
        exit();
    }

    private function assignAdvisor(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../frontend/AssignStudentsToAdvisors.php?msg=invalid_request');
            exit();
        }

        $studentId = isset($_POST['student_id']) ? (int) $_POST['student_id'] : 0;
        $advisorId = isset($_POST['advisor_id']) ? (int) $_POST['advisor_id'] : 0;

        if ($studentId <= 0 || $advisorId <= 0) {
            header('Location: ../../frontend/AssignStudentsToAdvisors.php?msg=missing_data');
            exit();
        }

        $success = $this->adminModel->assignAdvisorToStudent($studentId, $advisorId);

        if ($success) {
            header('Location: ../../frontend/AssignStudentsToAdvisors.php?msg=assigned_success');
            exit();
        }

        header('Location: ../../frontend/AssignStudentsToAdvisors.php?msg=assigned_error');
        exit();
    }

    private function removeAdvisor(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../frontend/AssignStudentsToAdvisors.php?msg=invalid_request');
            exit();
        }

        $studentId = isset($_POST['student_id']) ? (int) $_POST['student_id'] : 0;

        if ($studentId <= 0) {
            header('Location: ../../frontend/AssignStudentsToAdvisors.php?msg=missing_data');
            exit();
        }

        $success = $this->adminModel->removeAdvisorFromStudent($studentId);

        if ($success) {
            header('Location: ../../frontend/AssignStudentsToAdvisors.php?msg=removed_success');
            exit();
        }

        header('Location: ../../frontend/AssignStudentsToAdvisors.php?msg=removed_error');
        exit();
    }

    private function searchStudents(): void
    {
        $keyword = trim($_GET['keyword'] ?? '');

        if ($keyword === '') {
            $students = $this->adminModel->getAllStudents();
        } else {
            $students = $this->adminModel->searchStudents($keyword);
        }

        $_SESSION['admin_students'] = $students;

        header('Location: ../../frontend/ManageStudents.php');
        exit();
    }

    private function searchAdvisors(): void
    {
        $keyword = trim($_GET['keyword'] ?? '');

        if ($keyword === '') {
            $advisors = $this->adminModel->getAllAdvisors();
        } else {
            $advisors = $this->adminModel->searchAdvisors($keyword);
        }

        $_SESSION['admin_advisors'] = $advisors;

        header('Location: ../../frontend/ManageAdvisors.php');
        exit();
    }
}

$controller = new AdminController();
$controller->handleRequest();