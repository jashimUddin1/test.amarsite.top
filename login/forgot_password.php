

<!-- forget_password.php -->
<?php 
session_start();
$page_title = "Forgot Password";
require '../db/dbcon.php';


$resend_available = false;
$remaining_seconds = 60;

if (isset($_SESSION['reset_mail'])) {
    $email = $_SESSION['reset_mail'];
    
    $query = "SELECT reset_at FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $reset_a = strtotime($row['reset_at']);
        $reset_at = $reset_a - 14400;
        $now = time();

        $date = date('Y-m-d H:i:s', $now);
        $elapsed = $now - $reset_at;

        // ⏲️ ১০ মিনিট পার হয়ে গেলে সেশন রিমুভ করে দাও
        if ($elapsed >= 600) {
            unset($_SESSION['reset_mail']);
            unset($_SESSION['status']);
        }

        // ⏱️ ২ মিনিটের হিসাব (রিসেন্ডের জন্য)
        if ($elapsed >= 240) {
            $resend_available = true;
        } else {
            $remaining_seconds = 240 - $elapsed;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
              <?php include'includes/session.php' ?>
                <div class="card">
                    <div class="card-header d-flex justify-content-between ">
                        <h4>Reset Password</h4>
                        <button onclick="window.location.href='index.php';" class="btn btn-secondary">Back</button>
                    </div>
                    <div class="card-body">
                        <form action="reset_password_code.php" method="POST">
                            <div class="form-group mb-3">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <?php if (!isset($_SESSION['reset_mail'])): ?>
                                    <button type="submit" name="reset_password_btn" class="btn btn-primary">Send Reset Link</button>
                                <?php endif ?>
                                    
                                    <?php if (isset($_SESSION['reset_mail'])): ?>
                                        <?php if ($resend_available): ?>
                                            <span class="h5 ">Didn't receive reset link? <a class="text-decoration-none btn btn-primary" href="reset_password_code.php?resend=1&email=<?= $_SESSION['reset_mail'] ?>">Send Again</a></span>
                                        <?php else: ?>
                                            <span class="text-muted h5">Didn't receive reset link? 
                                                <a class="text-muted text-decoration-none" href="#">Send Again</a> 
                                                <span class="text-primary"> in <span id="timer"><?= $remaining_seconds ?></span>s </span>
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                
                                
                                <?php if (!$resend_available): ?>
                                <script>
                                    let seconds = <?= $remaining_seconds ?>;
                                    const timer = document.getElementById('timer');
                                
                                    const interval = setInterval(() => {
                                        seconds--;
                                        if (seconds <= 0) {
                                            clearInterval(interval);
                                            location.reload(); // রিলোড করে নতুন করে চেক করবে
                                        } else {
                                            timer.textContent = seconds;
                                        }
                                    }, 1000);
                                </script>
                                <?php endif; ?>


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
