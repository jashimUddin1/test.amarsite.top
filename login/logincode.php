<!-- logincode.php -->
<?php
session_start();
require '../db/dbcon.php';

if (isset($_SESSION['authenticated'])) {
    header("Location: ../index.php");
    exit(0);
}

if (isset($_POST['login_now_btn'])) {
    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);

        $check_user_query = "SELECT * FROM `users` WHERE `email`='$email' LIMIT 1";
        $check_user_query_run = mysqli_query($con, $check_user_query);

        if (mysqli_num_rows($check_user_query_run) > 0) {
            $user_data = mysqli_fetch_array($check_user_query_run);

            if ($user_data["verify_status"] == "1") { // ✅ Email Verify চেক ✅
                if (password_verify($password, $user_data['password'])) {
                    $_SESSION['authenticated'] = true;
                    $_SESSION['auth_user'] = [
                        'id' => $user_data['id'],
                        'fname' => $user_data['first_name'],
                        'lname' => $user_data['last_name'],
                        'email' => $user_data['email'],
                        'phone' => $user_data['phone'],
                        'role' => $user_data['role']
                    ];

                    // login info collect
                    $user_id = $user_data['id'];
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $device = $_SERVER['HTTP_USER_AGENT'];

                    // privious login time
                    $prev_login = NULL;
                    $get_last = "SELECT login_time FROM login_logs WHERE user_id='$user_id' ORDER BY login_time DESC LIMIT 1";
                    $last_run = mysqli_query($con, $get_last);
                    if(mysqli_num_rows($last_run) > 0){
                        $last = mysqli_fetch_assoc($last_run);
                        $prev_login = $last['login_time'];
                    }

                    // try to find out location
                    $location = "Unknown";
                    $details = json_decode(file_get_contents("https://tools.keycdn.com/geo?host=$ip"));
                    if(isset($details->city) && isset($details->country)){
                        $location = $details->city . ", " . $details->country;
                    }

                     // login info insert
                    $log_query = "INSERT INTO login_logs (user_id, ip_address, device_info, location, previous_login)
                    VALUES ('$user_id', '$ip', '$device', '$location', " . ($prev_login ? "'$prev_login'" : "NULL") . ")";
                    mysqli_query($con, $log_query);

                    $_SESSION['success'] = "Login successful!";
                    header("Location: ../index.php");
                    exit(0);
                } else {
                    $_SESSION['danger'] = "Invalid Password!";
                    header("Location: index.php");
                    exit(0);
                }
            } else {
                $_SESSION['warning'] = "Please verify your email address to login!";
                header("Location: index.php");
                exit(0);
            }
        } else {
            $_SESSION['warning'] = "No Account Found with this Email!. Please <a href='register.php'>create</a> Acount";
            header("Location: index.php");
            exit(0);
        }
    }
}
?>
