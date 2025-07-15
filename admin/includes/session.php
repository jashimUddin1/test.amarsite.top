<?php 
if (isset($_SESSION['status'])) {
    echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert'>
        {$_SESSION['status']}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
    unset($_SESSION['status']);
}

if (isset($_SESSION['success'])) {
    echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert'>
        {$_SESSION['success']}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
    unset($_SESSION['success']);
}

if (isset($_SESSION['warning'])) {
    echo "
    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
        {$_SESSION['warning']}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
    unset($_SESSION['warning']);
}

if (isset($_SESSION['danger'])) {
    echo "
    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
        {$_SESSION['danger']}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
    unset($_SESSION['danger']);
}
?>