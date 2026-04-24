<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =========================
   FETCH USER EMAIL
========================= */
$user_email_sql = "SELECT email FROM users WHERE id = '$user_id'";
$email_result = $conn->query($user_email_sql);
$email_data = $email_result->fetch_assoc();
$user_email = $email_data['email'];

/* =========================
   FETCH CYCLE DATA
========================= */
$sql = "SELECT * FROM cycles WHERE user_id = '$user_id' ORDER BY start_date DESC";
$result = $conn->query($sql);

/* =========================
   LAST CYCLE
========================= */
$sql_last = "SELECT * FROM cycles WHERE user_id = '$user_id' ORDER BY start_date DESC LIMIT 1";
$result_last = $conn->query($sql_last);
$last_cycle = ($result_last && $result_last->num_rows > 0)
    ? $result_last->fetch_assoc()
    : null;

/* =========================
   AVERAGE CYCLE
========================= */
$sql_avg = "SELECT AVG(cycle_length) as avg_length FROM cycles WHERE user_id = '$user_id'";
$result_avg = $conn->query($sql_avg);
$avg_data = $result_avg->fetch_assoc();

$avg_length = ($avg_data['avg_length']) ? round($avg_data['avg_length']) : 0;
$next_period = "No Data";

if ($last_cycle && $avg_length > 0) {
    $last_start = $last_cycle['start_date'];
    $next_period = date(
        'd M Y',
        strtotime($last_start . " + $avg_length days")
    );
}

/* =========================
   TOTAL CYCLES
========================= */
$total_cycles_sql = "SELECT COUNT(*) as total FROM cycles WHERE user_id = '$user_id'";
$total_result = $conn->query($total_cycles_sql);
$total_data = $total_result->fetch_assoc();
$total_cycles = $total_data['total'];

/* =========================
   AI SMART PREDICTION
========================= */
$ai_sql = "SELECT start_date, cycle_length
           FROM cycles
           WHERE user_id = '$user_id'
           ORDER BY start_date DESC
           LIMIT 6";

$result_ai = $conn->query($ai_sql);

$cycle_lengths = [];
$last_start_date = null;

if ($result_ai && $result_ai->num_rows > 0) {
    while ($row = $result_ai->fetch_assoc()) {
        $cycle_lengths[] = $row['cycle_length'];

        if ($last_start_date === null) {
            $last_start_date = $row['start_date'];
        }
    }
}

$total_weight = 0;
$weighted_sum = 0;
$weight = count($cycle_lengths);

foreach ($cycle_lengths as $length) {
    $weighted_sum += ($length * $weight);
    $total_weight += $weight;
    $weight--;
}

$smart_avg_cycle = 0;

if ($total_weight > 0) {
    $smart_avg_cycle = round($weighted_sum / $total_weight);
}

$next_predicted_period = "Not enough data";

if ($last_start_date && $smart_avg_cycle > 0) {
    $next_predicted_period = date(
        'd M Y',
        strtotime($last_start_date . " + $smart_avg_cycle days")
    );
}

/* =========================
   PERIOD REMINDER SYSTEM
========================= */
$reminder_message = "";
$days_left = 999;

if ($next_predicted_period != "Not enough data") {

    $today = new DateTime();
    $next_date = new DateTime($next_predicted_period);

    $days_left = $today->diff($next_date)->days;

    if ($today > $next_date) {
        $reminder_message = "🔴 Your expected period date has passed. Please update your cycle.";
    } elseif ($days_left == 0) {
        $reminder_message = "⚠ Your period is expected today.";
    } elseif ($days_left == 1) {
        $reminder_message = "⚠ Your period is expected tomorrow.";
    } elseif ($days_left <= 5) {
        $reminder_message = "⚠ Your next period is expected in $days_left days.";
    } else {
        $reminder_message = "✅ Your cycle is on track.";
    }
}

/* =========================
   EMAIL NOTIFICATION SYSTEM
========================= */

if ($days_left <= 2) {
    include 'modules/notifications/send_email.php';

    sendReminderEmail(
        $user_email,
        $_SESSION['name'],
        "Your next period is expected in $days_left day(s). Please stay prepared 🌸"
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - WellFlow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Dark Mode CSS -->
    <link rel="stylesheet" href="assets/css/theme.css">

    <!-- PWA -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#6f42c1">

    <style>
        .navbar {
            background: #6f42c1;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 24px;
        }

        .main-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: white;
        }

        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 25px;
            background: white;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            height: 100%;
        }

        .stat-title {
            font-size: 15px;
            color: #666;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            margin-top: 10px;
        }

        .btn-main {
            background: #6f42c1;
            color: white;
            border-radius: 10px;
            border: none;
        }

        .btn-main:hover {
            background: #5a32a3;
            color: white;
        }

        .table-card {
            border-radius: 18px;
            overflow: hidden;
        }

        .welcome-text {
            font-size: 28px;
            font-weight: bold;
        }

        .sub-text {
            color: #666;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">🌸 WellFlow</a>

        <div class="ms-auto">

            <button onclick="toggleDarkMode()" class="btn btn-light btn-sm me-2">
                🌙 Dark Mode
            </button>

            <a href="calendar.php" class="btn btn-light btn-sm me-2">Calendar</a>

            <a href="modules/cycle/add_cycle.php" class="btn btn-light btn-sm me-2">
                Add Cycle
            </a>

            <a href="modules/doctor/book_doctor.php" class="btn btn-outline-success me-2">
                👩‍⚕ Book Doctor
            </a>

            <a href="chatbot.php" class="btn btn-outline-info me-2">
                🤖 AI Assistant
            </a>

            <a href="generate_report.php" class="btn btn-light btn-sm me-2">
                PDF Report
            </a>

            <a href="logout.php" class="btn btn-danger btn-sm">
                Logout
            </a>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <!-- WELCOME -->
    <div class="main-card p-4 mb-4">
        <div class="welcome-text">
            Welcome, <?php echo $_SESSION['name']; ?> 👋
        </div>
        <p class="sub-text mb-0">
            Track your cycle, predict your next period, manage health, book consultations, and use AI support.
        </p>
    </div>

    <!-- REMINDER -->
    <?php if (!empty($reminder_message)) { ?>
        <div class="alert alert-warning shadow-sm mb-4" style="border-radius: 14px;">
            <strong>Reminder:</strong>
            <?php echo $reminder_message; ?>
        </div>
    <?php } ?>

    <!-- STATS -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-title">📅 Next Predicted Period</div>
                <div class="stat-value text-primary">
                    <?php echo $next_period; ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-title">📊 Average Cycle Length</div>
                <div class="stat-value text-success">
                    <?php echo $avg_length ? $avg_length . " Days" : "No Data"; ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-title">📋 Total Cycles</div>
                <div class="stat-value text-dark">
                    <?php echo $total_cycles; ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-title">🤖 Smart AI Prediction</div>
                <div class="stat-value text-danger">
                    <?php echo $next_predicted_period; ?>
                </div>

                <small class="text-muted">
                    Based on last <?php echo count($cycle_lengths); ?> cycles
                </small>
            </div>
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <div class="main-card p-4 mb-4">
        <h4 class="mb-3">Quick Actions</h4>

        <a href="modules/cycle/add_cycle.php" class="btn btn-main me-2">
            ➕ Add New Cycle
        </a>

        <a href="calendar.php" class="btn btn-outline-primary me-2">
            📅 View Calendar
        </a>

        <a href="modules/doctor/book_doctor.php" class="btn btn-outline-success me-2">
            👩‍⚕ Doctor Consultation
        </a>

        <a href="chatbot.php" class="btn btn-outline-info me-2">
            🤖 AI Assistant
        </a>

        <a href="generate_report.php" class="btn btn-outline-dark">
            📄 Download Report
        </a>
    </div>

    <!-- CYCLE HISTORY -->
    <div class="main-card table-card p-4">
        <h4 class="mb-3">Cycle History</h4>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Cycle Length</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo date('d M Y', strtotime($row['start_date'])); ?></td>
                        <td><?php echo date('d M Y', strtotime($row['end_date'])); ?></td>
                        <td><?php echo $row['cycle_length']; ?> Days</td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='3'>No cycle data found</td></tr>";
                    }
                    ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

<!-- DARK MODE SCRIPT -->
<script>
function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("theme", "dark");
    } else {
        localStorage.setItem("theme", "light");
    }
}

window.onload = function () {
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");
    }
}
</script>

<!-- SERVICE WORKER -->
<script>
if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("service-worker.js")
        .then(() => console.log("Service Worker Registered"));
}
</script>

</body>
</html>