<?php
include "../db/dbcon.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['auth_user']['id'])) {
    $_SESSION['danger'] = "Please login. then try it";
    header("Location: ../login/index.php");
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_month_btn'])) {
    $user_id = $_SESSION['auth_user']['id'];

    $selected_year = isset($_POST['selected_year']) ? intval($_POST['selected_year']) : null;
    
    // Custom month or current month in full string (e.g., "May")
    $custom_month = isset($_POST['custom_month']) && $_POST['custom_month'] != "" 
                ? $_POST['custom_month'] 
                : date('F');

    // Convert month name to month number
    $month_number = date('n', strtotime("1 " . $custom_month)); // "May" → 5

    // Calculate how many days in the month
    $month_day = cal_days_in_month(CAL_GREGORIAN, $month_number, $selected_year); // e.g., 30 or 31

    if ($selected_year && $custom_month) {
        $check_sql = "SELECT * FROM `month` WHERE `user_id` = ? AND `year` = ? AND `month` = ?";
        $stmt = $con->prepare($check_sql);
        $stmt->bind_param("iis", $user_id, $selected_year, $custom_month);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['warning'] = "This month already exists under selected year.";
        } else {
            $insert_sql = "INSERT INTO `month` (`user_id`, `year`, `month`,`day`) VALUES (?, ?, ?, ?)";
            $insert_stmt = $con->prepare($insert_sql);
            $insert_stmt->bind_param("iisi", $user_id, $selected_year, $custom_month, $month_day);
            
            if ($insert_stmt->execute()) {
                $_SESSION['success'] = "Month added successfully!";
            } else {
                $_SESSION['warning'] = "Error adding month. Please try again.";
            }
        }
    } else {
        $_SESSION['danger'] = "Invalid year or month.";
    }

    header("Location: index.php?year=$selected_year&month=$custom_month");
    exit();
}
?>
