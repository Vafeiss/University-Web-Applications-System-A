<?php
/*Name: Change Password Controller
Description: This controller is responsible for handling the change password process of users.
Paraskevas Vafeiadis
26-feb-2026 v0.1
Inputs: Old Password, New Password , confirm new password
Outputs: Object of users
Error Messages: if wrong request method -> error
if not logged in -> error
if empty fields -> error
if new password and confirm new password do not match -> error
if new password is weak -> error
if old password is wrong -> error
Files in use: UsersClass.php where the change_password method is.
*/
declare(strict_types=1);

// session must be started to access $_SESSION
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../modules/UsersClass.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { //check if the page is accessed with a post request
    header('Location: ../../changepassword.php');
    exit();
}

if (!isset($_SESSION['UserID'])) {  //check if the user is logged in
    header('Location: ../../index.php?error=not_logged_in');
    exit();
}
//trim the inputs for empty checks
$oldpassword = trim($_POST['currentPassword'] ?? '');
$newpassword = trim($_POST['newPassword'] ?? '');
$confirmNewPassword = trim($_POST['confirmNewPassword'] ?? '');

//validation checks for empty fields , password match and password strength
if ($oldpassword === '' || $newpassword === '' || $confirmNewPassword === '') {
    header('Location: ../../changepassword.php?error=empty_fields');
    exit();
}

if ($newpassword !== $confirmNewPassword) {
    header('Location: ../../changepassword.php?error=passwords_do_not_match');
    exit();
}

if (strlen($newpassword) < 6) {
    header('Location: ../../changepassword.php?error=weak_password');
    exit();
}

$oldpassword   = trim($_POST['currentPassword'] ?? '');
$newpassword = $_POST['newPassword'] ?? '';

//create object call function
$user = new Users();
$result = $user->Change_Password($_SESSION['UserID'], $oldpassword, $newpassword);

//if the result is true password changed go to index if else try again
if ($result === true) {
    header('Location: ../../frontend/index.php');
} else {
    header('Location: ../../changepassword.php?error=wrong_password');
}

exit();

