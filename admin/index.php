<!-- admin/index.php -->
<?php
session_start();

if (!isset($_SESSION['authenticated'])) {
    header("location: ../login/index.php");
}
    $page_title = "Admin Panel";
    include "../db/dbcon.php";

    $user_id = $_SESSION['auth_user']['id'];
    $sql = "SELECT defaultRate FROM users WHERE id = ? LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($defaultRate);
    $stmt->fetch();
    $stmt->close();

    include '../includes/header_title.php';
?>
    <link href="css/admin.css" rel="stylesheet" type="text/css">
    <style>
        .colspan-3 {
            flex: 1 1 100%;
        }
    </style>
<?php
    include('../includes/header_end.php');
    include('includes/admin_bar.php');

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <?php include('includes/session.php'); ?>
                
            <div class="card-body">             
                <div class="table-responsive">
                    <?php include 'includes/fetch_for_admin.php'; ?>
                </div>


                  <div class="form-container mt-4" >
                    <form id="myForm" style="display: flex; flex-wrap: wrap;" action="save_data.php" method="POST">
                        <input type="hidden" name="year" value="<?php echo htmlspecialchars($year); ?>">
                        <input type="hidden" name="month" value="<?php echo htmlspecialchars($month); ?>">

                        <div class="input-field">
                            <input type="date" id="input1" name="input1" required placeholder="Date"/>
                        </div>
                        <div class="input-field">
                            <input type="text" id="input2" name="input2" required placeholder="Received" />
                        </div>
                        <div class="input-field">
                            <input type="number" id="input3" name="input3" required placeholder="Cancel" />
                        </div>
                        <div class="input-field">
                            <input type="number" id="input5" name="input5" required placeholder="Reschedule" />
                        </div>
                        <div class="input-field">
                            <input type="number" value="<?php echo $defaultRate; ?>"  id="input4" name="input4" required placeholder="Rate" />
                        </div>
                        <button class="btn btn-primary" name="submit_btn" id="submitBtn" type="submit">Submit</button>
                    </form>
                </div>

            </div>                       
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const receivedInput = document.getElementById("input2");
    const cancelInput = document.getElementById("input3");
    const rescheduleInput = document.getElementById("input5");

    const cancelField = cancelInput.closest(".input-field");
    const rescheduleField = rescheduleInput.closest(".input-field");
    const receivedField = receivedInput.closest(".input-field");

    const offDayValues = ["off day", "holiday", "eid holiday", "absent", "govt holiday"];

    receivedInput.addEventListener("input", function () {
        const val = receivedInput.value.trim().toLowerCase();

        if (offDayValues.includes(val)) {
            // value 0 set
            cancelInput.value = 0;
            rescheduleInput.value = 0;

            // hide fields
            cancelField.style.display = "none";
            rescheduleField.style.display = "none";

            // make received field wider (optional visual effect)
            receivedField.style.width = "51%"; // colspan effect
        } else {
            // reset visibility
            cancelField.style.display = "block";
            rescheduleField.style.display = "block";
            receivedField.style.width = "17%";

            // reset layout
            receivedField.style.flex = "";
        }
    });
});
</script>

<?php include('../includes/footer.php'); ?>
