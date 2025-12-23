<?php
session_start();

// Include database connection
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attapp/database/database.php"; // $conn is defined here

$error = "";

// Check if form is submitted
if (isset($_POST['login'])) {
    // Escape user input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check credentials
    $sql = "SELECT * FROM faculty_details WHERE user_name='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $_SESSION['faculty'] = $username;
        header("Location: /attapp/dashboard/dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Faculty Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
    /* Reset everything */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        height: 100%;
        width: 100%;
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #87CEFA, #8A2BE2);
    }

    .login-container {
        background: rgba(255, 255, 255, 0.15);
        padding: 40px 30px;
        border-radius: 15px;
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        width: 350px;
        max-width: 90%;
        text-align: center;
        animation: fadeIn 1s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    h2 {
        color: #fff;
        margin-bottom: 25px;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        margin: 10px 0;
        border: none;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
    }

    input[type="submit"] {
        width: 100%;
        padding: 12px;
        margin-top: 15px;
        border: none;
        border-radius: 8px;
        background: #8A2BE2;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    input[type="submit"]:hover {
        background: #5D00B3;
    }

    .error {
        color: #ff6b6b;
        margin-bottom: 15px;
    }

    ::placeholder {
        color: #555;
    }
</style>
</head>
<body>

<div class="login-container">
    <h2>Faculty Login</h2>
    <?php if($error) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>
</div>

</body>
</html>
