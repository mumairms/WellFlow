<?php
session_start();
include '../../config/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../../login.php");
    exit();
}

if(isset($_POST['submit'])){
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $mood = $_POST['mood'];
    $symptoms = $_POST['symptoms'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO symptoms (user_id, date, mood, symptoms, notes)
            VALUES ('$user_id', '$date', '$mood', '$symptoms', '$notes')";

    if($conn->query($sql)){
        echo "Data Saved Successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Symptoms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4 shadow">
        <h3>🧠 Add Mood & Symptoms</h3>

        <form method="POST">

            <label>Date</label>
            <input type="date" name="date" class="form-control" required><br>

            <label>Mood</label>
            <select name="mood" class="form-control">
                <option>Happy</option>
                <option>Sad</option>
                <option>Angry</option>
                <option>Anxious</option>
                <option>Tired</option>
            </select><br>

            <label>Symptoms</label>
            <input type="text" name="symptoms" class="form-control" placeholder="Cramps, Headache"><br>

            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea><br>

            <button name="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

</body>
</html>