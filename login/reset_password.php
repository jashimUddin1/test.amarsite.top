<?php
session_start();
require '../db/dbcon.php';

if (!isset($_GET['token'])) {
    $_SESSION['status'] = "Invalid token!";
    header("Location: forgot_password.php");
    exit();
}

$token = mysqli_real_escape_string($con, $_GET['token']);
$check_token_query = "SELECT * FROM `users` WHERE reset_token='$token' LIMIT 1";
$check_token_query_run = mysqli_query($con, $check_token_query);

if (mysqli_num_rows($check_token_query_run) == 0) {
    $_SESSION['status'] = "Invalid or expired token!";
    header("Location: forgot_password.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                  <?php include'includes/session.php'; ?>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Set New Password</h4>
                            <button onclick="window.history.back();" class="btn btn-secondary">Back</button>
                        </div>
                        <div class="card-body">
                            <form action="update_password.php" method="POST">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <div class="form-group mb-3">
                                    <label>New Password</label>
                                    <input id="confirm_password" type="password" name="new_password" class="form-control" required>
                                    <small id="password-error" class="error-msg" style="color:red"></small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="confirm_password" class="form-control" required>
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            Show
                                        </button>
                                    </div>
                                    <small id="confirm-password-error" class="error-msg" style="color:red"></small>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="update_password" name="update_password_btn" class="btn btn-primary">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementsByName('new_password')[0];
    const confirmPasswordField = document.getElementsByName('confirm_password')[0];
    const passwordError = document.getElementById('password-error');
    const confirmPasswordError = document.getElementById('confirm-password-error');

    togglePassword.addEventListener('click', () => {
        const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordField.setAttribute('type', type);
        togglePassword.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    passwordField.addEventListener('input', () => {
        if (passwordField.value.length < 8) {
            passwordError.textContent = "Password must be at least 8 characters.";
        } else {
            passwordError.textContent = "";
        }
    });

    confirmPasswordField.addEventListener('input', () => {
        if (confirmPasswordField.value !== passwordField.value) {
            confirmPasswordError.textContent = "Password and Confirm Password do not match.";
        } else {
            confirmPasswordError.textContent = "";
        }
    });
</script>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>