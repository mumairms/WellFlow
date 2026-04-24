<?php
session_start();
include 'config/db.php';

/*
Simple admin access
(For now using email check)
*/

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/*
Change this email to your admin email
*/
$admin_email = "mohammedumairms@gmail.com";

/* Get logged-in user */
$user_id = $_SESSION['user_id'];

$user_sql = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_sql);
$current_user = $user_result->fetch_assoc();

if ($current_user['email'] != $admin_email) {
    die("Access Denied. Admin only.");
}

/* Fetch all users */
$users_sql = "SELECT * FROM users ORDER BY id DESC";
$users_result = $conn->query($users_sql);

/* Fetch all cycles */
$cycles_sql = "
    SELECT cycles.*, users.name
    FROM cycles
    JOIN users ON cycles.user_id = users.id
    ORDER BY cycles.id DESC
";
$cycles_result = $conn->query($cycles_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - WellFlow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f6f8fc;
            font-family: Arial;
        }

        .main-card {
            background: white;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
        }

        h2 {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container mt-5">

    <div class="main-card">
        <h2>🛡 Admin Panel</h2>
        <p class="text-muted">
            Manage users and monitor platform activity
        </p>
    </div>

    <!-- USERS -->
    <div class="main-card">
        <h4>Registered Users</h4>

        <table class="table table-bordered mt-3">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created</th>
            </tr>

            <?php
            while ($row = $users_result->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <!-- CYCLES -->
    <div class="main-card">
        <h4>All Cycle Records</h4>

        <table class="table table-bordered mt-3">
            <tr>
                <th>User</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Cycle Length</th>
            </tr>

            <?php
            while ($row = $cycles_result->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['end_date']; ?></td>
                <td><?php echo $row['cycle_length']; ?> Days</td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

</body>
</html>