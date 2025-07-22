
<!-- reset_password_code.php -->
<?php
session_start();
require '../db/dbcon.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$now = new DateTime();
$now->modify('+4 hours');
$time = $now->format('Y-m-d H:i:s');

// Resend Option from from forget_password.php
if (isset($_GET['resend']) && $_GET['resend'] == 1 && isset($_GET['email'])) {
    $_POST['reset_password_btn'] = true;
    $_POST['email'] = $_GET['email'];
}


if (isset($_POST['reset_password_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $token = md5(rand());

    $check_email_query = "SELECT * FROM `users` WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        $user = mysqli_fetch_assoc($check_email_query_run);

         // Increment reset_count
         $current_count = $user['reset_count'] ?? 0;
         $new_count = $current_count + 1;

        // Update token, time, and count
        $update_token_query = "UPDATE `users` SET reset_token='$token', reset_at='$time', reset_count=$new_count WHERE email='$email'";

        if (mysqli_query($con, $update_token_query)) {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'developermdjasim@gmail.com'; 
            $mail->Password = 'izts stcl gvkm gnql';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('developermdjasim@gmail.com', 'Developer Jasim');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Reset Password";
            $mail->Body = "
                <h4>Reset your password</h4>
                <p>Click the link below to reset your password:</p>
                <a href='https://salary.amarsite.top/login/reset_password.php?token=$token'>Reset Password</a>

                <p> Thanks in Advance. Enjoy 🙂😊</p>
            ";

            if ($mail->send()) {
                $_SESSION['status'] = "Password reset link sent! Check your email.";
                $_SESSION["reset_mail"] = $email;
                header("Location: forgot_password.php");
            } else {
                $_SESSION['status'] = "Failed to send email.";
                header("Location: forgot_password.php");
            }
        }
    } else {
        $_SESSION['status'] = "No account found with this email.";
        header("Location: forgot_password.php");
    }
}
