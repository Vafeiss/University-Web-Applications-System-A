<?php 
/* NAME: log-In page
   Description: This is the login page for the web application without any backend for now. 
   It contains a simple form with username and password fields and the sumbit button,
   It will send the data to the auth.php to check the credintials and log the user in.
   Paraskevas Vafeiadis
   23-feb-2026
   Inputs: Username,Password
   Outputs: None
   Error Messages : Field not filled. (1)
   Files in use: Bootstrap CSS from the web
   */


?>
<?php 
// (1)
$error = " "
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>University Web Applications System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="Log-in body">
        <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width:400px;">
        <h3 class="text-center mb-4">University Advisor Platform</h3>
        <form method = "POST" action = "login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="Submit" class="btn btn-primary w-100">Login</button>
        </form>
        </div>
        </div>
    </body>
</html>

