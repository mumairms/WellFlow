<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ======================
   PERIOD DAYS
====================== */
$period_days = [];

$sql = "SELECT start_date, end_date FROM cycles WHERE user_id = $user_id";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    $start = strtotime($row['start_date']);
    $end = strtotime($row['end_date']);

    for($date = $start; $date <= $end; $date += 86400){
        $period_days[] = date('Y-m-d', $date);
    }
}

/* ======================
   SYMPTOMS
====================== */
$symptom_days = [];

$sym_sql = "SELECT date FROM symptoms WHERE user_id = $user_id";
$sym_result = $conn->query($sym_sql);

while($row = $sym_result->fetch_assoc()){
    $symptom_days[] = $row['date'];
}

/* ======================
   PREDICTION
====================== */
$sql_last = "SELECT * FROM cycles WHERE user_id = $user_id ORDER BY start_date DESC LIMIT 1";
$result_last = $conn->query($sql_last);
$last_cycle = $result_last->fetch_assoc();

$sql_avg = "SELECT AVG(cycle_length) as avg_length FROM cycles WHERE user_id = $user_id";
$result_avg = $conn->query($sql_avg);
$avg_data = $result_avg->fetch_assoc();

$avg_length = ($avg_data['avg_length']) ? round($avg_data['avg_length']) : 28;

$predicted_days = [];

if($last_cycle){
    $last_start = strtotime($last_cycle['start_date']);
    $next_start = strtotime("+$avg_length days", $last_start);

    for($i=0; $i<5; $i++){
        $predicted_days[] = date('Y-m-d', strtotime("+$i days", $next_start));
    }
}

/* ======================
   CURRENT MONTH
====================== */
$month = date('m');
$year = date('Y');

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$first_day = date('w', strtotime("$year-$month-01"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calendar</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }

        .day {
            padding: 20px;
            background: white;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 5px #ccc;
        }

        .period { background: #ff4d6d; color: white; }
        .symptom { background: #ffc107; }
        .prediction { background: #4dabf7; color: white; }
    </style>
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2>📅 Calendar</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

    <div class="calendar">

        <!-- Empty spaces before first day -->
        <?php for($i=0; $i<$first_day; $i++){ ?>
            <div></div>
        <?php } ?>

        <!-- Days -->
        <?php for($day=1; $day <= $days_in_month; $day++){ 
            $current_date = "$year-$month-" . str_pad($day, 2, "0", STR_PAD_LEFT);

            $class = "";

            if(in_array($current_date, $period_days)){
                $class = "period";
            } elseif(in_array($current_date, $predicted_days)){
                $class = "prediction";
            } elseif(in_array($current_date, $symptom_days)){
                $class = "symptom";
            }
        ?>

        <div class="day <?php echo $class; ?>">
            <?php echo $day; ?>
        </div>

        <?php } ?>

    </div>

</div>

</body>
</html>