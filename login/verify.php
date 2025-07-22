<?php
session_start();
require '../db/dbcon.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $check_token_query = "SELECT id FROM `users` WHERE verify_token='$token' LIMIT 1";
    $check_token_query_run = mysqli_query($con, $check_token_query);

    if (mysqli_num_rows($check_token_query_run) > 0) {
        $userData = mysqli_fetch_assoc($check_token_query_run);
        $user_id = $userData['id'];

        // Update user verify status
        $update_query = "UPDATE `users` SET verify_token=NULL, verify_status=1, updated_at=NOW() WHERE id='$user_id' LIMIT 1";
        $update_query_run = mysqli_query($con, $update_query);

        if ($update_query_run) {
            // Get current year and month
            $year = date('Y');
            $month = date('F');

            // Calculate number of days in that month
            $month_number = date('n');
            $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);


            // Check if the month already exists for this user
            $check_month_query = "SELECT id FROM `month` WHERE user_id='$user_id' AND year='$year' AND month='$month' LIMIT 1";
            $check_month_result = mysqli_query($con, $check_month_query);

            if (mysqli_num_rows($check_month_result) == 0) {
                // Insert new month with number of days
                $insert_month_query = "INSERT INTO `month` (user_id, year, month, day) VALUES ('$user_id', '$year', '$month', '$days_in_month')";
                mysqli_query($con, $insert_month_query);
            }

            $_SESSION['status'] = "Email verified successfully. You can now log in.";
            unset($_SESSION["last_registered_email"]);
            header("Location: index.php");
            exit(0);
        } else {
            $_SESSION['status'] = "Verification failed. Please try again.";
            header("Location: register.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Invalid or expired token.";
        header("Location: register.php");
        exit(0);
    }
} else {
    $_SESSION['status'] = "No token provided.";
    header("Location: register.php");
    exit(0);
}
?>
