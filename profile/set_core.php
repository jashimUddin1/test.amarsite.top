<?php // setDefaultRate_core.php
session_start();
include "../db/dbcon.php";

if (!isset($_SESSION['authenticated'])) {
    header('location: ../login');
    exit(0);
}

if (isset($_POST['setDefaultRate_btn'])) {
    $user_id = $_SESSION['auth_user']['id'];
    $newRate = $_POST['rate'];

    // Validation
    if (!is_numeric($newRate) || $newRate < 20 || $newRate > 40) {
        $_SESSION['status'] = "Rate must be between 20 and 40.";
        header("Location: profile.php");
        exit(0);
    }

    // Fetch current defaultRate
    $selectQuery = "SELECT defaultRate FROM users WHERE id = ?";
    $stmtSelect = $con->prepare($selectQuery);
    $stmtSelect->bind_param('i', $user_id);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $currentRate = $row['defaultRate'];

        // যদি পুরনো আর নতুন মান এক হয়
        if ($currentRate == $newRate) {
            $_SESSION['warning'] = "The value is the same as previous value.";
            header("Location: profile.php");
            exit(0);
        }

        // Update defaultRate
        $updateQuery = "UPDATE users SET defaultRate = ? WHERE id = ?";
        $stmtUpdate = $con->prepare($updateQuery);
        $stmtUpdate->bind_param('di', $newRate, $user_id);

        if ($stmtUpdate->execute()) {
            $_SESSION['status'] = "Default rate updated successfully!";
            header("Location: profile.php");
            exit(0);
        } else {
            $_SESSION['warning'] = "Something went wrong. Please try again.";
            header("Location: profile.php");
            exit(0);
        }

    } else {
        $_SESSION['status'] = "User not found!";
        header("Location: profile.php");
        exit(0);
    }

    
} else  if (isset($_POST['set_riderType_btn'])) {
    $user_id = $_SESSION['auth_user']['id'];
    $riderType = $_POST['riderType'];


    // Fetch current riderType
    $selectQuery = "SELECT 	riderType FROM users WHERE id = ?";
    $stmtSelect = $con->prepare($selectQuery);
    $stmtSelect->bind_param('i', $user_id);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $currentType = $row['riderType'];

        // যদি পুরনো আর নতুন মান এক হয়
        if ($riderType == $currentType) {
            $_SESSION['warning'] = "The value is the same as previous value.";
            header("Location: profile.php");
            exit(0);
        }



        // Update defaultRate
        $updateQuery = "UPDATE users SET riderType = ? WHERE id = ?";
        $stmtUpdate = $con->prepare($updateQuery);
        $stmtUpdate->bind_param('si', $riderType, $user_id);

        if ($stmtUpdate->execute()) {
            $_SESSION['status'] = "Rider Type updated successfully!";
            header("Location: profile.php");
            exit(0);
        } else {
            $_SESSION['warning'] = "Something went wrong. Please try again.";
            header("Location: profile.php");
            exit(0);
        }

    } else {
        $_SESSION['status'] = "User not found!";
        header("Location: profile.php");
        exit(0);
    }
    
}else  if (isset($_POST['setBesicSalary_btn'])) {
    $user_id = $_SESSION['auth_user']['id'];
    $basic_salary = $_POST['salary'];


    // Fetch current basic_salary
    $selectQuery = "SELECT basic_salary FROM users WHERE id = ?";
    $stmtSelect = $con->prepare($selectQuery);
    $stmtSelect->bind_param('i', $user_id);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $currentType = $row['basic_salary'];

        // যদি পুরনো আর নতুন মান এক হয়
        if ($basic_salary == $currentType) {
            $_SESSION['warning'] = "The value is the same as previous value.";
            header("Location: profile.php");
            exit(0);
        }



        // Update defaultRate
        $updateQuery = "UPDATE users SET basic_salary = ? WHERE id = ?";
        $stmtUpdate = $con->prepare($updateQuery);
        $stmtUpdate->bind_param('si', $basic_salary, $user_id);

        if ($stmtUpdate->execute()) {
            $_SESSION['status'] = "Besic Salary updated successfully!";
            header("Location: profile.php");
            exit(0);
        } else {
            $_SESSION['warning'] = "Something went wrong. Please try again.";
            header("Location: profile.php");
            exit(0);
        }

    } else {
        $_SESSION['status'] = "User not found!";
        header("Location: profile.php");
        exit(0);
    }
    
} else{
    $_SESSION['danger'] = "Unauthorized access!";
    header("Location: profile.php");
    exit(0);
}
?>
