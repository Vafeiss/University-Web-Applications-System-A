<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
</head>
<body>
    <h1>Welcome to the Student Dashboard</h1>
    <button onclick="location.href='changepassword.php'">Change Password</button>
    <!-- use dispatcher to route actions; include hidden field for logout -->
    <form action="../backend/modules/dispatcher.php" method="POST">
        <input type="hidden" name="action" value="logout">
        <button type="submit">Logout</button>
    </form>
</body>
</html>