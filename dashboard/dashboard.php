<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['faculty'])) {
    header("Location: /attapp/login/index.php");
    exit();
}

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attapp/database/database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Faculty Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #87CEFA, #8A2BE2);
        overflow: hidden;
    }

    .dashboard-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        border-radius: 25px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.35);
        width: 420px;
        max-width: 90%;
        padding: 50px 35px;
        text-align: center;
        color: #fff;
        animation: fadeIn 0.8s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .dashboard-container h1 {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #fff;
        text-shadow: 1px 1px 10px rgba(0,0,0,0.2);
    }

    .welcome-text {
        font-size: 18px;
        color: #e0e0e0;
        margin-bottom: 35px;
    }

    .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 15px 0;
        margin: 12px 0;
        background: linear-gradient(135deg, #00CFFF, #8A2BE2);
        border: none;
        border-radius: 15px;
        color: #fff;
        font-size: 17px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.4s ease;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    }

    .btn:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 25px rgba(138, 43, 226, 0.5);
    }

    .logout-btn {
        background: linear-gradient(135deg, #FF6B6B, #B30000);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.35);
    }

    .logout-btn:hover {
        box-shadow: 0 10px 25px rgba(255, 107, 107, 0.5);
    }

    /* Optional: Icon styling */
    .btn svg {
        font-size: 18px;
    }

</style>
</head>
<body>

<div class="dashboard-container">
    <h1>Faculty Dashboard</h1>
    <p class="welcome-text">Welcome, <strong><?php echo htmlspecialchars($_SESSION['faculty']); ?></strong> 👋</p>

    <a href="/attapp/dashboard/take_attendance.php" class="btn">📋 Take Attendance</a>
    <a href="/attapp/dashboard/view_attendance.php" class="btn">📊 View Attendance</a>
    <a href="/attapp/logout.php" class="btn logout-btn">🚪 Logout</a>
</div>

</body>
</html>
