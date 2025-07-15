
<?php
// logout.php

ob_start(); // Output buffering শুরু

session_start();
include '../db/dbcon.php';

// যদি ইউজার লগইন করা থাকে
if (isset($_SESSION['auth_user']['id'])) {
    $user_id = $_SESSION['auth_user']['id'];
    $logout_time = date("Y-m-d H:i:s");

    // ✅ ১. logout_time আপডেট করো (শেষ লগিন লগের)
    $query = "UPDATE login_logs SET logout_time = ? WHERE user_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = mysqli_prepare($con, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $logout_time, $user_id);
        mysqli_stmt_execute($stmt);
    }

    // ✅ ২. remember_token ফাঁকা করো
    $query2 = "UPDATE users SET remember_token = NULL WHERE id = ?";
    $stmt2 = mysqli_prepare($con, $query2);
    if ($stmt2) {
        mysqli_stmt_bind_param($stmt2, "i", $user_id);
        mysqli_stmt_execute($stmt2);
    }
}




// ✅ ৫. Session unset এবং destroy
$_SESSION = [];
session_destroy();

ob_end_flush(); // Output buffering বন্ধ

// ✅ ৬. রিডাইরেক্ট করে index.php তে পাঠাও
header("Location: index.php");
exit;
?>
