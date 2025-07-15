<?php
session_start();
if (isset($_SESSION['status'])) {
    unset($_SESSION['status']);
    exit();
}
?>

