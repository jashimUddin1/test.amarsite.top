
<?php // delete_data.php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../db/dbcon.php';

if (isset($_POST['delete_count'])) {
    $user_id = $_SESSION['auth_user']['id'];
    $data_id = mysqli_real_escape_string($con, $_POST['data_id']);
    $year = mysqli_real_escape_string($con, $_POST['year']);
    $month = mysqli_real_escape_string($con, $_POST['month']);

    $query = "DELETE FROM `data` WHERE id='$data_id' AND user_id='$user_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['warning'] = "Data deleted successfully";
        header("Location: index.php?year=$year&month=$month");
        exit(0);
    } else {
        $_SESSION['danger'] = "Data deletion failed";
        header("Location: index.php?year=$year&month=$month");
        exit(0);
    }
}

