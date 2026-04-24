<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $doctor_name = $_POST['doctor_name'];
    $appointment_date = $_POST['appointment_date'];
    $issue = $_POST['issue'];

    $sql = "INSERT INTO doctor_bookings
            (user_id, doctor_name, appointment_date, issue)
            VALUES
            ('$user_id', '$doctor_name', '$appointment_date', '$issue')";

    if ($conn->query($sql)) {
        $success = "Doctor consultation booked successfully!";
    } else {
        $error = "Something went wrong.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Doctor Consultation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f6f8fc;
            font-family: Arial;
        }

        .main-box {
            max-width: 700px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        h2 {
            font-weight: bold;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
        }

        .btn-main {
            background: #6f42c1;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
        }

        .btn-main:hover {
            background: #5b34a0;
            color: white;
        }
    </style>
</head>
<body>

<div class="main-box">

    <h2>👩‍⚕ Book Doctor Consultation</h2>

    <?php if (!empty($success)) { ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
    <?php } ?>

    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <div class="mb-3">
            <label>Doctor Name</label>
            <input
                type="text"
                name="doctor_name"
                class="form-control"
                placeholder="Enter doctor name"
                required
            >
        </div>

        <div class="mb-3">
            <label>Appointment Date</label>
            <input
                type="date"
                name="appointment_date"
                class="form-control"
                required
            >
        </div>

        <div class="mb-3">
            <label>Your Issue / Concern</label>
            <textarea
                name="issue"
                class="form-control"
                rows="4"
                placeholder="Describe your concern"
                required
            ></textarea>
        </div>

        <button name="submit" class="btn btn-main">
            Book Appointment
        </button>

        <a href="../../dashboard.php" class="btn btn-outline-secondary ms-2">
            Back
        </a>

    </form>

</div>

</body>
</html>