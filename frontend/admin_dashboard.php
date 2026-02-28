<?php
require_once('init.php');
require_once("../backend/modules/UsersClass.php");
$user = new Users();
$user->Check_Session("Admin");
?>
this is a test for admin log in
