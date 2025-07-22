

<!-- index.php -->
<?php
session_start();
require '../db/dbcon.php';
if (isset($_SESSION['authenticated'])) {
    header("location: ../index.php");
}

$page_title = "Login Page";
include('includes/header.php');

?>
<style>
    body{
        /* background: -webkit-linear-gradient(left, #003366, #004080, #0059b3, #0073e6); */
        background:-webkit-linear-gradient(left, #95a5b4, #728daa, #85a4c4, #797f85);
    }
</style>

<div class="py-1 mt-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <!-- 🔰 Company Logo + Name -->
                <div class="shadow d-flex justify-content-between text-center">
                    <img src="../images/logo.png" alt="image" style="max-width: 200px; max-height: 75px;">
                    <h2 class="fw-bold mt-3 text-white mx-3">Salary Count</h2>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

              <?php include'includes/session.php' ?>

                <div class="card p-2">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Login form</h5>
                        <button onclick="window.history.back();" class="btn btn-secondary px-3">Back</button>
                    </div>
                    <div class="card-body">
                        <form action="logincode.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Email Address</label>
                                <input type="text" name="email" class="form-control" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control" required>
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        Show
                                    </button>
                                </div>
                            </div>

                            <!-- <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input border-dark" name="remember_me" id="remember_me">
                                <label class="form-check-label" for="remember_me">Remember Me</label>
                            </div> -->


                            <?php 
                                if (isset($_GET['status']) && $_GET['status'] == 0 && isset($_SESSION['last_registered_email'])) {
                                    $email = $_SESSION['last_registered_email'];

                                    $query = "SELECT created_at FROM users WHERE email='$email' LIMIT 1";
                                    $result = mysqli_query($con, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        $row = mysqli_fetch_assoc($result);
                                        $createdA = strtotime($row['created_at']);
                                        $createdAt = $createdA - 14400;
                                        $now = time();
                                        $elapsed = $now - $createdAt;

                                        $resend_available = false;
                                        $remaining_seconds = 150;

                                        // ⏲️ ১০ মিনিট পার হয়ে গেলে সেশন রিমুভ করে দাও
                                        if ($elapsed >= 600) {
                                            unset($_SESSION['last_registered_email']);
                                            unset($_SESSION['status']);
                                        }

                                        if ($elapsed >= 150) {
                                            $resend_available = true;
                                        } else {
                                            $remaining_seconds = 150 - $elapsed;
                                        }
                                        ?>

                                        <div class="mb-3">
                                            <?php if ($resend_available): ?>
                                                <h5>
                                                    Didn’t receive email?
                                                    <a href="resend_email.php?email=<?= $email ?>">Resend</a>
                                                </h5>
                                            <?php else: ?>
                                                <h5 class="disabled">
                                                    Didn’t receive email?
                                                    <span class="text-muted">Resend in <span id="timer"><?= $remaining_seconds ?></span>s</span>
                                                </h5>

                                                <script>
                                                    let seconds = <?= $remaining_seconds ?>;
                                                    const timer = document.getElementById('timer');

                                                    const interval = setInterval(() => {
                                                        seconds--;
                                                        if (seconds <= 0) {
                                                            clearInterval(interval);
                                                            location.reload(); // Reload page to show resend link
                                                        } else {
                                                            timer.textContent = seconds;
                                                        }
                                                    }, 1000);
                                                </script>
                                            <?php endif; ?>
                                        </div>

                                    <?php
                                    }
                                }
                            ?>

                            <div class="d-flex justify-content-center mb-2 mt-2">
                                <button type="submit" id="loginNow" name="login_now_btn" class="btn btn-primary">Login Now</button>                              
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a>
                            </div>
                    </div>
                    </form>
                    <hr>          
                    
                    <h5 class="container mx-auto d-flex justify-content-between">
                        <span>Did not account?</span>
                        <a id="" class="btn btn-success" href="register.php">Create New Account</a>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');

    togglePassword.addEventListener('click', () => {
        // Password field type toggle (password <-> text)
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        // Button text toggle (Show <-> Hide)
        togglePassword.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    function updateColClass() {
        const targetElements = document.querySelectorAll('.col-md-10');

        targetElements.forEach(function (el) {
            // Reset previous classes
            el.classList.remove('col-md-10', 'col-md-6', 'col-md-8');

            if (window.innerWidth > 1200) {
                el.classList.add('col-md-6');
            } else if (window.innerWidth > 790 && window.innerWidth <= 1200) {
                el.classList.add('col-md-8');
            } else {
                el.classList.add('col-md-10');
            }
        });
    }

    // Run on page load
    window.addEventListener('DOMContentLoaded', updateColClass);

    // Run on window resize
    window.addEventListener('resize', updateColClass);

</script>

<?php include('includes/footer.php'); ?>