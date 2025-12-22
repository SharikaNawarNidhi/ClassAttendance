<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['faculty'])) {
    header("Location: /attapp/login/index.php");
    exit();
}

// Optional: Include database connection if you need to fetch data
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attapp/database/database.php"; // $conn is defined here
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculty Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        a { text-decoration: none; color: blue; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h1>Welcome, <?php echo htmlspecialchars($_SESSION['faculty']); ?>!</h1>

<p>
    <a href="/attapp/logout.php">Logout</a>
</p>

<!-- Optional: Links to other faculty functionalities -->
<p>
    <a href="/attapp/dashboard/take_attendance.php">Take Attendance</a><br>
    <a href="/attapp/dashboard/view_attendance.php">View Attendance</a>
</p>

</body>
</html>
