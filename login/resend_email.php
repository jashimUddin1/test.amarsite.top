<?php
session_start();
require '../db/dbcon.php';
require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;

if (!isset($_GET['email'])) {
    $_SESSION['status'] = "Invalid request!";
    header("Location: index.php");
    exit(0);
}

$email = mysqli_real_escape_string($con, $_GET['email']);

// ইউজার খোঁজা
$query = "SELECT id, email, verify_token, verify_status, created_at FROM users WHERE email = '$email' LIMIT 1";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // যদি verify_status = 1 হয়, তাহলে মেসেজ দেখাবে
    if ($user['verify_status'] == 1) {
        $_SESSION['status'] = "Your email is already verified.";
        header("Location: index.php");
        exit(0);
    }

    // যদি verify_token না থাকে, নতুন টোকেন তৈরি
    $verify_token = $user['verify_token'];

    // ইমেইল পাঠানো
    if (sendVerificationEmail($email, $verify_token)) {
        $_SESSION['status'] = "Verification has been resent your email again! Make sure to check Spam folder in gmail account";
        header("Location: index.php");
        exit(0);
    } else {
        $_SESSION['status'] = "Failed to resend email!";
        header("Location: index.php?status=0");
        exit(0);
    }

} else {
    $_SESSION['status'] = "No such email found!";
    header("Location: index.php");
    exit(0);
}

function sendVerificationEmail($email, $verify_token) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'denapawna.amarsite.top';
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply@denapawna.amarsite.top';
    $mail->Password = 'TFdU9e;Ap*66';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('noreply@denapawna.amarsite.top', 'Developer Jasim');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Verify your email";
    $mail->Body = "
        <h2>Welcome back!</h2>
        <p>Click the link below to verify your email address:</p>
        <a href='http://localhost/myrunningproject/version-2.02-salary/login/verify.php?token=$verify_token'>Verify Email</a>
        <p>Thanks again 😊</p>
    ";

    return $mail->send();
}
