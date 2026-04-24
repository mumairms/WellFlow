<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Convert to DateTime
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);

    // Validation: end date cannot be before start date
    if ($end < $start) {
        die("End date cannot be before Start date");
    }

    // Calculate cycle length (+1 to include both start and end day)
    $cycle_length = $start->diff($end)->days + 1;

    $sql = "INSERT INTO cycles (user_id, start_date, end_date, cycle_length)
            VALUES ('$user_id', '$start_date', '$end_date', '$cycle_length')";

    if ($conn->query($sql)) {
        echo "Cycle Added Successfully";
        header("refresh:2;url=../../dashboard.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Cycle</title>
</head>
<body>

<h2>Add Cycle</h2>

<form method="POST">
    <label>Start Date:</label><br>
    <input type="date" name="start_date" required><br><br>

    <label>End Date:</label><br>
    <input type="date" name="end_date" required><br><br>

    <button name="submit">Save</button>
</form>

<br>
<a href="../../dashboard.php">← Back to Dashboard</a>

</body>
</html>