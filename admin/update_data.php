<?php
session_start();
include '../db/dbcon.php';

if (isset($_POST['update_count'])) {

    $data_id = mysqli_real_escape_string($con, $_POST['data_id']);
    $year = mysqli_real_escape_string($con, $_POST['year']);
    $month = mysqli_real_escape_string($con, $_POST['month']);

    $date = mysqli_real_escape_string($con, $_POST['date']);
    $received = mysqli_real_escape_string($con, $_POST['received']);
    $cancel = mysqli_real_escape_string($con, $_POST['cancel']);
    $reschedule = mysqli_real_escape_string($con, $_POST['reschedule']);
    $return = $cancel + $reschedule;
    $delivered = $received - $return;
    $rate = mysqli_real_escape_string($con, $_POST['rate']);
    $total = $delivered * $rate;

    if($return > $received){
        $_SESSION['warning'] = "You entered False information. the return cann't be bigger then Received";
        header("Location: index.php?year=$year&month=$month");
        exit(0);
    }

    $query = "UPDATE `data` SET date='$date', received='$received', cancel='$cancel', reschedule='$reschedule', delivered='$delivered', rate='$rate', total='$total' WHERE id='$data_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['success'] = "Data updated successfully";
        header("Location: index.php?year=$year&month=$month");
        exit(0);
    } else {
        $_SESSION['danger'] = "Data update failed";
        header("Location: index.php?year=$year&month=$month");
        exit(0);
    }
}
