<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../lib/PHPMailer/src/Exception.php';
require '../../lib/PHPMailer/src/PHPMailer.php';
require '../../lib/PHPMailer/src/SMTP.php';

function sendReminderEmail($to_email, $user_name, $message)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        /*
        Use your Gmail here
        */
        $mail->Username = 'yourgmail@gmail.com';
        $mail->Password = 'your_app_password';

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender
        $mail->setFrom('yourgmail@gmail.com', 'WellFlow');

        // Receiver
        $mail->addAddress($to_email, $user_name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'WellFlow Reminder Notification';
        $mail->Body = "
            <h3>Hello $user_name 👋</h3>
            <p>$message</p>
            <br>
            <p>Stay healthy 🌸</p>
            <p><strong>WellFlow Team</strong></p>
        ";

        $mail->send();

        return true;

    } catch (Exception $e) {
        return false;
    }
}
?>