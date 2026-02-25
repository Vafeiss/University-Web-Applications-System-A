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

25-feb-2026 v0.2
Added new database schema with Users table send the query to the user table and then based on the role
send the user to the right dashboard.
*/

session_start();
class Users {
private $conn;

public function __construct() {
    //creating an obj of the mysql connection and connect to the database 
    $this->conn = new mysqli("localhost", "root", "", "advicut");

if ($this->conn->connect_error) { //if connection fails kill it and print message
    die("Connection failed: " . $this->conn->connect_error);
}
mysqli_select_db($this->conn,"advicut");
}

//method to log in the user by checking email and password to the advicut database
public function Log_in(string $email, string $password) {
    //query to get all the students where email and password match the input parameters 
    $sql = "SELECT * FROM Users WHERE Uni_Email = ? AND Password = ?";
    $stmt1 = $this->conn->prepare($sql);
    $stmt1->bind_param("ss", $email, $password); //make the query as a prepared statement to prevent attacks
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    
    if($result1->num_rows == 1) {
        $row = $result1->fetch_assoc();
        $_SESSION['email'] = $row['Uni_Email']; //storing info while user logged in
        $_SESSION['UserID'] = $row['UserID'];
        $_SESSION['role'] = $row['Role'];

        if ($_SESSION['role'] == 'Student') {
            header("Location: ../../frontend/student_dashboard.php");
            exit();
        }
        else if ($_SESSION['role'] == 'Advisor') {
            header("Location: ../../frontend/advisor_dashboard.php");
            exit();
        }
        else if ($_SESSION['role'] == 'Admin') {
            header("Location: ../../frontend/admin_dashboard.php");
            exit();
        }
        else if ($_SESSION['role'] == 'SuperUser') {
            header("Location: ../../frontend/SuperUser_dashboard.php");
            exit();
        }
    }
    else {
        echo "Invalid email or password.";
        header("location: ../../frontend/index.php");
    }
}
//method to kill the session of the user and log them out.
public function Log_out() {
    session_destroy();
}


public function ValidateCredentials($email, $password) {


}

//method to reset the one time password of the user to his own.
public function Signin($email, $password) {

}
}
?>