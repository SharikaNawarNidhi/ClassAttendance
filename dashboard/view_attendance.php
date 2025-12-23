<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['faculty'])) {
    header("Location: /attapp/login/index.php");
    exit();
}
// Include database connection
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attapp/database/database.php"; // $conn

$faculty_user = $_SESSION['faculty'];
$message = "";

// Fetch courses and sessions
$courses = mysqli_query($conn, "SELECT id, code, title FROM course_details ORDER BY title");
$sessions = mysqli_query($conn, "SELECT id, year, term FROM session_details ORDER BY year DESC");

// Handle filter submission
$attendance_records = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'];
    $session_id = $_POST['session_id'];
    $on_date = $_POST['on_date'];

    // Get faculty id
    $faculty_query = mysqli_query($conn, "SELECT id FROM faculty_details WHERE user_name='$faculty_user'");
    $faculty_row = mysqli_fetch_assoc($faculty_query);
    $faculty_id = $faculty_row['id'];

    // Fetch attendance records
    $sql = "
        SELECT s.roll_no, s.name, a.status
        FROM attendance_details a
        JOIN student_details s ON a.student_id = s.id
        WHERE a.faculty_id='$faculty_id'
        AND a.course_id='$course_id'
        AND a.session_id='$session_id'
        AND a.on_date='$on_date'
        ORDER BY s.roll_no ASC
    ";
    $attendance_records = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Attendance</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }

    body {
        background: linear-gradient(135deg, #87CEFA, #FF4C4C);
        min-height: 100vh;
        padding: 40px 20px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .container {
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        box-shadow: 0 10px 35px rgba(0,0,0,0.3);
        width: 900px;
        max-width: 95%;
        padding: 40px 30px;
        color: #fff;
        animation: fadeIn 0.8s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    h1 {
        font-size: 28px;
        font-weight: 600;
        text-align: center;
        margin-bottom: 25px;
        text-shadow: 1px 1px 10px rgba(0,0,0,0.2);
    }

    a.back-btn {
        display: inline-block;
        margin-bottom: 20px;
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    a.back-btn:hover {
        text-decoration: underline;
    }

    form label { font-weight: 500; margin-bottom: 5px; display: block; }

    select, input[type="date"] {
        padding: 10px;
        border-radius: 10px;
        border: none;
        width: 250px;
        margin-bottom: 20px;
        outline: none;
        font-size: 15px;
    }

    input[type="submit"] {
        background: linear-gradient(135deg, #00CFFF, #FF4C4C);
        color: #fff;
        padding: 12px 25px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        margin-top: 10px;
        width: 100%;
        transition: all 0.3s ease;
    }

    input[type="submit"]:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 6px 20px rgba(255,76,76,0.4);
    }

    .msg { text-align: center; font-weight: bold; margin-bottom: 20px; }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
        overflow: hidden;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        font-size: 15px;
    }

    th { background: rgba(255,255,255,0.15); }

    tr:hover { background: rgba(255,255,255,0.1); }

    .no-records { text-align:center; color: #ffcccc; margin-top:20px; font-weight:500; }

    /* Scrollable table */
    .table-wrapper { max-height: 400px; overflow-y: auto; border-radius: 10px; }

</style>
</head>
<body>

<div class="container">
    <h1>View Attendance</h1>
    <a class="back-btn" href="/attapp/dashboard/dashboard.php">⬅ Back to Dashboard</a>

    <form method="POST" action="">
        <label><strong>Select Course:</strong></label>
        <select name="course_id" required>
            <option value="">-- Choose Course --</option>
            <?php while ($c = mysqli_fetch_assoc($courses)) { ?>
                <option value="<?php echo $c['id']; ?>" <?php if(isset($course_id) && $course_id==$c['id']) echo "selected"; ?>>
                    <?php echo htmlspecialchars($c['code'] . " - " . $c['title']); ?>
                </option>
            <?php } ?>
        </select>

        <label><strong>Select Session:</strong></label>
        <select name="session_id" required>
            <option value="">-- Choose Session --</option>
            <?php while ($s = mysqli_fetch_assoc($sessions)) { ?>
                <option value="<?php echo $s['id']; ?>" <?php if(isset($session_id) && $session_id==$s['id']) echo "selected"; ?>>
                    <?php echo htmlspecialchars($s['year'] . " - " . $s['term']); ?>
                </option>
            <?php } ?>
        </select>

        <label><strong>Date:</strong></label>
        <input type="date" name="on_date" value="<?php echo isset($on_date) ? $on_date : date('Y-m-d'); ?>" required>

        <input type="submit" value="View Attendance">
    </form>

    <?php if (!empty($attendance_records) && mysqli_num_rows($attendance_records) > 0): ?>
        <h2 style="margin-top:30px;">Attendance Records</h2>
        <div class="table-wrapper">
            <table>
                <tr>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($attendance_records)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p class="no-records">⚠️ No attendance records found for the selected course/session/date.</p>
    <?php endif; ?>
</div>

</body>
</html>
