<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$response = "";

if (isset($_POST['ask'])) {
    $question = strtolower(trim($_POST['question']));

    // Basic chatbot logic
    if (strpos($question, "late period") !== false) {
        $response = "A late period can happen due to stress, hormonal imbalance, diet changes, or health conditions. If it continues, consult a doctor.";
    }
    elseif (strpos($question, "period pain") !== false) {
        $response = "Period pain is common, but severe pain may need medical attention. Stay hydrated and consult a doctor if pain is intense.";
    }
    elseif (strpos($question, "irregular cycle") !== false) {
        $response = "Irregular cycles can happen due to stress, lifestyle, PCOS, or hormonal imbalance. Tracking regularly helps identify patterns.";
    }
    elseif (strpos($question, "pregnancy") !== false) {
        $response = "Missed periods may sometimes indicate pregnancy, but there can be other reasons too. Please consult a healthcare professional.";
    }
    else {
        $response = "I recommend tracking your symptoms and consulting a doctor for accurate medical advice.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AI Assistant - WellFlow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f6f8fc;
            font-family: Arial;
        }

        .chat-box {
            max-width: 800px;
            margin: 60px auto;
            background: white;
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        h2 {
            font-weight: bold;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 12px;
            padding: 14px;
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

        .response-box {
            margin-top: 30px;
            background: #f8f5ff;
            border-left: 5px solid #6f42c1;
            padding: 20px;
            border-radius: 12px;
        }
    </style>
</head>
<body>

<div class="chat-box">

    <h2>🤖 AI Health Assistant</h2>
    <p class="text-muted">
        Ask basic menstrual health questions and get quick guidance.
    </p>

    <form method="POST">

        <div class="mb-3">
            <label>Your Question</label>
            <textarea
                name="question"
                class="form-control"
                rows="4"
                placeholder="Example: Why is my period late?"
                required
            ></textarea>
        </div>

        <button name="ask" class="btn btn-main">
            Ask Assistant
        </button>

        <a href="dashboard.php" class="btn btn-outline-secondary ms-2">
            Back
        </a>

    </form>

    <?php if (!empty($response)) { ?>
        <div class="response-box">
            <h5>Assistant Response:</h5>
            <p><?php echo $response; ?></p>
        </div>
    <?php } ?>

</div>

</body>
</html>