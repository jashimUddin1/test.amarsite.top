<?php
session_start();
require '../db/dbcon.php';
$now = new DateTime();
$now->modify('+4 hours');
$time = $now->format('Y-m-d H:i:s');

if (isset($_POST['update_password_btn'])) {
    $token = mysqli_real_escape_string($con, $_POST['token']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    if (strlen($new_password) < 8) {
        $_SESSION['status'] = "Password must be at least 8 characters.";
        header("Location: reset_password.php?token=$token");
        exit(0);
    } elseif ($new_password === $confirm_password && strlen($new_password) >= 8) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $queryCount = "SELECT pwd_up_count FROM users WHERE reset_token='$token' LIMIT 1";
        $countResult = mysqli_query($con, $queryCount);

        $current_count = 0;

        if ($countResult && mysqli_num_rows($countResult) > 0) {
            $row = mysqli_fetch_assoc($countResult);
            $current_count = (int)$row['pwd_up_count'];
        }

        // Step 2: নতুন count হিসাব করা
        $new_count = $current_count + 1;
       
        $update_password_query = "UPDATE `users` SET password='$hashed_password', reset_token='', pwd_up_at='$time', pwd_up_count='$new_count' WHERE reset_token='$token'";
        if (mysqli_query($con, $update_password_query)) {
            $_SESSION['status'] = "Password updated successfully! Please login.";
            header("Location: index.php");
            exit(0);
        } else {
            $_SESSION['status'] = "Failed to update password.";
            header("Location: reset_password.php?token=$token");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Passwords do not match.";
        header("Location: reset_password.php?token=$token");
        exit(0);
    }
}
?>
