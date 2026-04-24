<?php
session_start();
include 'config/db.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "Account not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | WellFlow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #f6f3ff, #eef5ff);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .main-wrapper {
            width: 100%;
            max-width: 1100px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(14px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        }

        .left-panel {
            background: linear-gradient(135deg, #6f42c1, #8b5cf6);
            color: white;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left-panel h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .left-panel p {
            font-size: 16px;
            line-height: 1.8;
            opacity: 0.95;
        }

        .feature-box {
            margin-top: 30px;
        }

        .feature-box div {
            margin-bottom: 14px;
            font-size: 15px;
        }

        .right-panel {
            padding: 60px 50px;
            background: rgba(255,255,255,0.9);
        }

        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand h2 {
            font-weight: 700;
            color: #2d2d2d;
        }

        .brand p {
            color: #777;
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            height: 52px;
            border-radius: 14px;
            border: 1px solid #ddd;
            padding-left: 15px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #8b5cf6;
            box-shadow: none;
        }

        .btn-main {
            background: #6f42c1;
            border: none;
            height: 52px;
            border-radius: 14px;
            color: white;
            font-weight: 600;
            font-size: 15px;
            transition: 0.3s;
        }

        .btn-main:hover {
            background: #5b34a0;
        }

        .bottom-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .bottom-text a {
            text-decoration: none;
            font-weight: 600;
            color: #6f42c1;
        }

        .alert {
            border-radius: 14px;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .main-wrapper {
                grid-template-columns: 1fr;
            }

            .left-panel {
                display: none;
            }

            .right-panel {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>

<div class="main-wrapper">

    <!-- LEFT PANEL -->
    <div class="left-panel">
        <h1>🌸 WellFlow</h1>
        <p>
            Smart menstrual health tracking platform with AI prediction,
            reminder alerts, reports, consultation booking and personalized care.
        </p>

        <div class="feature-box">
            <div><i class="bi bi-check-circle"></i> Smart AI Cycle Prediction</div>
            <div><i class="bi bi-check-circle"></i> PDF Health Reports</div>
            <div><i class="bi bi-check-circle"></i> Reminder Notifications</div>
            <div><i class="bi bi-check-circle"></i> Doctor Consultation</div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">

        <div class="brand">
            <h2>Welcome Back 👋</h2>
            <p>Login to continue your health journey</p>
        </div>

        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter your email"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <div class="d-grid mt-4">
                <button name="login" class="btn btn-main">
                    Login to Dashboard
                </button>
            </div>

        </form>

        <div class="bottom-text">
            Don’t have an account?
            <a href="register.php">Create Account</a>
        </div>

    </div>

</div>

</body>
</html>