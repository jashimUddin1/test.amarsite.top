<!-- register.php -->
<?php
session_start();
if (isset($_SESSION['authenticated'])) {
    header("location: ../index.php");
}
$page_title = "Registration Page";

include('includes/header.php');
?>
<style>
    body{
        background: -webkit-linear-gradient(left, #003366, #004080, #0059b3, #0073e6);
    }
</style>

<div class="py-1 mt-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <!-- Company Logo + Name -->
                <div class="shadow d-flex justify-content-between text-center">
                    <img src="../images/logo.png" alt="image" style="max-width: 200px; max-height: 80px;">
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
                <?php
                if (isset($_SESSION['status'])) {
                ?>
                    <div class="alert alert-success">
                        <h5><?= $_SESSION['status'] ?></h5>
                    </div>
                <?php
                    unset($_SESSION['status']);
                }
                ?>
                <div class="p-3 card shadow">
                    <div class="card-header d-flex justify-content-between">
                        <h2>Registration Form</h2>
                        <button onclick="window.location.href='index.php';" class="btn btn-secondary px-3">Login</button>
                    </div>
                    <div class="card-body">
                        <form class="fs-3" action="register_code.php" method="post">

                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <label for="fname">First Name</label>
                                    <input type="text" id="fname" name="fname" class="form-control form-control-lg" placeholder="First name" required>
                                    <small id="fname-error" class="error-msg" style="color:red"></small>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="lname">Last Name</label>
                                    <input type="text" name="lname" class="form-control form-control-lg" placeholder="Last name">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 form-group">
                                    <label class="mb-1" for="phone">Phone Number</label>
                                    <input type="number" name="phone" id="phone" class="form-control col-md-12 form-control-lg" placeholder="Your Phone number" required>
                                    <small id="phone-error" class="error-msg" style="color:red"></small>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Your Email Address" required>
                                    <small id="email-error" class="error-msg" style="color:red"></small>
                                </div>
                            </div>
                            

                            <div class="row mb-3">
                                <div class="col-md-6 form-group">
                                    <label for="
                                    ">Select Rider Type</label>
                                    <select style="width:100%" name="riderType" id="riderType" class="form-control form-control-lg">
                                        <option value="cyclist">By Cycle</option>
                                        <option value="biker">Biker</option>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="defaultRate">Set Default Rate</label>
                                    <input type="number" id="defaultRate" name="defaultRate" class="form-control form-control-lg" placeholder="Zone Rate (20-40)" required>
                                    <small id="defaultRate-error" class="error-msg" style="color:red"></small>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" placeholder="Enter your password" class="form-control form-control-lg" required>
                                </div>
                                <small id="signup-password-error" class="error-msg" style="color:red"></small>
                            </div>
                            <div class="form-group mb-3">
                                <label for="confirm_password">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter confirm password" class="form-control form-control-lg" required>
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">Show</button>
                                </div>
                                <small id="confirm-password-error" class="error-msg" style="color:red"></small>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input border-dark" type="checkbox" value="" id="invalidCheck2" required>
                                <label class="form-check-label" for="invalidCheck2">
                                    Agree to terms and conditions
                                </label>
                                <small id="checkbox-error" class="error-msg" style="color:red"></small>
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <button type="submit" name="register_btn" class="btn btn-success btn-lg col-md-10">Register Now</button>
                            </div>
                            
                        </form>
                        <hr>
                        <h5 class="mx-auto d-flex justify-content-center">
                            Already have an account? Click&nbsp; <a  href="index.php"> Here </a> &nbsp;to Login
                        </h5>
                    </div>
                </div>
            </div>
     </div>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('confirm_password');

    const signup_form = document.querySelector('form');
    const fname = document.getElementById('fname');
    const phone = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');
    const defaultRate = document.getElementById('defaultRate');
    const defaultRateError = document.getElementById('defaultRate-error');
    const signup_password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');
    const signup_password_error = document.getElementById('signup-password-error');
    const confirm_password_error = document.getElementById('confirm-password-error');
    const signup_email = document.getElementById('email');
    const email_error = document.getElementById('email-error');
    const termsCheckbox = document.getElementById('invalidCheck2');
    const termsError = document.getElementById('checkbox-error');

    //for password show/hide
    togglePassword.addEventListener('click', () => {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        togglePassword.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    phone.addEventListener('input', () => {
        if (phone.value.length < 9) {
            phoneError.textContent = "Your number is too short/Invalid Number";
        } else {
            phoneError.textContent = "";
        }
    });

    signup_email.addEventListener('input', () => {
        if (!signup_email.value.endsWith("@gmail.com")) {
            email_error.textContent = "Invalid email. Must be a Gmail address.";
        } else {
            email_error.textContent = "";
        }
    });

    defaultRate.addEventListener('input', () => {
        const value = parseInt(defaultRate.value);
        if (value < 20 || value > 40) {
            defaultRateError.textContent = "Default rate must be between 20 and 40.";
        } else {
            defaultRateError.textContent = "";
        }
    });

    signup_password.addEventListener('input', () => {
        if (signup_password.value.length < 8) {
            signup_password_error.textContent = "Password must be at least 8 characters";
        } else {
            signup_password_error.textContent = "";
        }

        if (confirm_password.value !== signup_password.value) {
            confirm_password_error.textContent = "Password and Confirm Password do not match";
        } else {
            confirm_password_error.textContent = "";
        }
    });

    confirm_password.addEventListener("input", () => {
        if (confirm_password.value !== signup_password.value) {
            confirm_password_error.textContent = "Password and Confirm Password do not match";
        } else {
            confirm_password_error.textContent = "";
        }
    });

    termsCheckbox.addEventListener('input', () => {
        if(!termsCheckbox.checked){
            termsError.textContent = "You must agree to the terms and conditions before submitting.";
        }else {
            termsError.textContent = "";
        }
    });

    // validation check and then submit
    function isFormValid() {
        let valid = true;
        const rateValue = parseInt(defaultRate.value);


        if (fname.value.trim() === "") valid = false;
        if (rateValue < 20 || rateValue > 40) valid = false;
        if (phone.value.length < 9) valid = false;
        if (!signup_email.value.endsWith("@gmail.com")) valid = false;
        if (signup_password.value.length < 8) valid = false;
        if (signup_password.value !== confirm_password.value) valid = false;
        if (!termsCheckbox.checked) valid = false;

        return valid;
    }

    // Enter press to go next fields with validation check  
    const inputs = document.querySelectorAll("input");

    inputs.forEach((input, index) => {
        input.addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
                event.preventDefault();
                const nextInput = inputs[index + 1];

                if (nextInput) {
                    nextInput.focus();
                } else {
                    if (isFormValid()) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'register_btn';
                        hiddenInput.value = 'true';

                        signup_form.appendChild(hiddenInput);
                        signup_form.submit();
                    }
                }
            }
        });
    });

    // validate form before submit
    signup_form.addEventListener('submit', function(event) {
        if (!isFormValid()) {
            event.preventDefault(); // Stop form submit
            alert("Please fix the errors before submitting.");
        }
    });


</script>



<?php include('includes/footer.php'); ?>
