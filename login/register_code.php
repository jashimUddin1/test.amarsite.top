
<?php //register_code.php
session_start();
require '../db/dbcon.php';
require 'vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;

$now = new DateTime();
$now->modify('+4 hours');
$time = $now->format('Y-m-d H:i:s');

if (isset($_POST['register_btn'])) {

    $user_ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $client_ip = $_SERVER['HTTP_CLIENT_IP'] ?? 'UNKNOWN';
    $remote_host = gethostbyaddr($user_ip) ?? 'UNKNOWN';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $riderType = mysqli_real_escape_string($con, $_POST['riderType']);
    $defaultRate = (int)mysqli_real_escape_string($con, $_POST['defaultRate']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    if (strlen($password) < 8) {
        $_SESSION['status'] = "Password must be at least 8 characters.";
        header("Location: register.php");
        exit(0);
    }

    if ($password !== $confirm_password) {
        $_SESSION['status'] = "Passwords do not match.";
        header("Location: register.php");
        exit(0);
    }
    
    if ($defaultRate < 20 || $defaultRate > 40) {
        $_SESSION['status'] = "Default Rate must be between 20 and 40.";
        header("Location: register.php");
        exit(0);
    }

    $hashed_password = password_hash($confirm_password, PASSWORD_DEFAULT); 
    $verify_token = md5(rand());

    // ইমেইল আগে থেকেই আছে কিনা চেক করা
    $check_email_query = "SELECT email FROM `users` WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        $_SESSION['status'] = "Email Id already exists.";
        header("Location: register.php");
        exit(0);
    } else {
        $query = "INSERT INTO `users`(`first_name`, `last_name`, `phone`, `email`, `password`, `verify_token`, `created_at`, `riderType`, `defaultRate`, `role`, `user_ip`, `client_ip`, `remote_host`,`user_agent`) VALUES ('$fname', '$lname', '$phone', '$email', '$hashed_password', '$verify_token', '$time', '$riderType', ' $defaultRate', 'user', '$user_ip', '$client_ip', '$remote_host', '$user_agent')";
        $query_run = mysqli_query($con, $query);

        if ($query_run) {
            // email padanor function
            if (sendVerificationEmail($email, $verify_token)) {
                $_SESSION['status'] = "Registration successful! Please check your email to verify your account. Make sure to check Spam folder in gmail account";
                $_SESSION['last_registered_email'] = $email;
                header("Location: index.php?status=0");
                exit(0);
            } else {
                $_SESSION['status'] = "Failed to send verification email.";
                header("Location: register.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Registration failed.";
            header("Location: register.php");
            exit(0);
        }
    }
}

function sendVerificationEmail($email, $verify_token) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'amarsite.top'; // Gmail SMTP হোস্ট
    $mail->SMTPAuth = true;
    $mail->Username = 'salary@amarsite.top';
    $mail->Password = 'KQe;74!ZFz=q';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('salary@amarsite.top', 'Developer Jasim');
    $mail->addReplyTo('salary@amarsite.top', 'Developer Jasim');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Verify your email";
    $mail->Body = "
        <h2>Welcome to our website!</h2>
        <p>Please Click the link to verify your email address</p>
        <a href='https://salary.amarsite.top/login/verify.php?token=$verify_token'>Verify Email</a>

        <p> Thanks in Advance. Enjoy 🙂😊</p>
        
        <p> if you are not get a link? then copy and manually verify please<p>
        link ==> https://salary.amarsite.top/login/verify.php?token=$verify_token
    ";
    $mail->AltBody = "Verify your email: https://salary.amarsite.top/login/verify.php?token=$verify_token";

    return $mail->send();
}

exit;
?>