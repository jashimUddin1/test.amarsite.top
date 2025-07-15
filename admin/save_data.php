
<!-- save_data.php -->
<?php
include("../db/dbcon.php");


// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if(isset($_POST['submit_btn']))
{
    // Retrieve the table name from the hidden input field
    $user_id = $_SESSION['auth_user']['id'];
    $tableName = 'data';
    $year = mysqli_real_escape_string($con, $_POST["year"]);
    $month = mysqli_real_escape_string($con, $_POST["month"]);

    // Retrieve form data
    $date = mysqli_real_escape_string($con, $_POST["input1"]);
    $recv = mysqli_real_escape_string($con, $_POST["input2"]);
    $recvInt = is_numeric($_POST["input2"]) ? (int) $_POST["input2"] : 0;
    $cancel = (int)mysqli_real_escape_string($con, $_POST["input3"]);
    $reschedule = (int)mysqli_real_escape_string($con, $_POST["input5"]);
    $delv = $recvInt - ($cancel + $reschedule);
    $rate = mysqli_real_escape_string($con, $_POST["input4"]);

    // Calculate total
    $total = $delv * $rate;

    // Prepare and execute SQL statement
    $sql = "INSERT INTO `$tableName` (user_id, year, month, date, received, cancel, reschedule, delivered, rate, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


$stmt = mysqli_prepare($con, $sql);

if ($stmt) {
  mysqli_stmt_bind_param($stmt, "issssiiiii",  $user_id, $year, $month, $date, $recv, $cancel, $reschedule, $delv, $rate, $total);
  mysqli_stmt_execute($stmt);
  
  $_SESSION['status'] = "Data inserted successfully";
  header("Location: index.php?year=$year&month=$month");
} else {
  $_SESSION['status'] = "Something went wrong!";
  header("Location: index.php?year=$year&month=$month");
}

mysqli_stmt_close($stmt);
// Close connection
}
?>
