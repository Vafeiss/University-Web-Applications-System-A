<?php
/*NAME: User Class
Description: This class is responsible for log_in,log_Out,validation of credentials and signing in(which is the way to reset the
one time password for the user)
Paraskevas Vafeiadis
24-feb-2026 v0.1
Inputs: Email,Password,database advicut
Outputs: None
Error Messages : Database connection failed.
Files in use: authentication.php where the object user is created and the log_in method is called,
student_dashboard.php to test the login of student and advisor_dashboard.php to test the login of the advisor
advicut.sql for the test with the database.
*/
session_start();
class Users {
    private $conn;

public function __construct() {
    
    $this->conn = new mysqli("localhost", "root", "", "advicut");

if ($this->conn->connect_error) {
    die("Connection failed: " . $this->conn->connect_error);
}
mysqli_select_db($this->conn,"advicut");
}

public function Log_in(string $email, string $password) {

    $sql = "SELECT * FROM student_info WHERE Uni_Email = ? AND OneTime_Password = ?";
    $stmt1 = $this->conn->prepare($sql);
    $stmt1->bind_param("ss", $email, $password);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    
    if($result1->num_rows == 1) {
        $row = $result1->fetch_assoc();
        $_SESSION['email'] = $row['Uni_Email'];
        $_SESSION['UserID'] = $row['StudentID'];
        $_SESSION['role'] = 'student';

        header("Location: ../../frontend/student_dashboard.php");
        exit();
    }
    
    $sql = "SELECT * FROM advisor_info WHERE Uni_Email = ? AND OneTime_Password = ?";
    $stmt2 = $this->conn->prepare($sql);
    $stmt2->bind_param("ss", $email, $password);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if($result2->num_rows == 1) {
        $row = $result2->fetch_assoc();
        $_SESSION['email'] = $row['Uni_Email'];
        $_SESSION['UserID'] = $row['AdvisorID'];
        $_SESSION['role'] = 'advisor';

        header("Location: ../../frontend/advisor_dashboard.php");
        exit();
    }

    header("Location: ../../frontend/index.php");
    exit();
}


public function Log_out() {

}

public function ValidateCredentials($email, $password) {


}

public function Signin($email, $password) {

}
}
?>