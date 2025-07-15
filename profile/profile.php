

<?php //profile.php 
    session_start();
    if (!isset($_SESSION['authenticated'])) {
        header("location: ../login/index.php");
    }
    include('../db/dbcon.php');
    $page_title = "Profile";
    include('../includes/header.php');
    include('../includes/profile_bar.php');

    $user_id = $_SESSION['auth_user']['id'];

    $query = "SELECT * FROM  users WHERE id = ?";
    $stmt = $con ->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt -> execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
        $userData = $result->fetch_assoc();
    } else {
        $_SESSION['warning'] = "User not found";
        exit(0);
    }
?>

<div class="container mt-4">

    <div class="row">
        <div class="col-md-12">
            <?php include '../admin/includes/session.php'; ?>

            <div class="card mb-5">
                <div class="card-header">
                    <h4>User Dashboard</h4>
                </div>
                <div class="card-body">
                    <h5>User Name: 
                        <?= $userData['first_name'] ?> 
                        <?= $userData['last_name'] ?> 
                    </h5> 
                    <!-- <h5>User Id: <?= $userData['id'] ?> </h5>  -->
                    <h5>Phone: <?= $userData['phone'] ?> </h5>                        
                    <h5>Email: <?= $userData['email'] ?> </h5>

                    <!--Rider type Default Rate Row -->
                    <div class="d-flex justify-content-between align-item-center mb-1">
                        <h5>Rider Type: <?= ucfirst(strtolower($userData['riderType'])) ?>
                        </h5>
                        <button class="btn btn-sm btn-primary" id="changeRiderType">Change</button>
                    </div>

                    <!-- Form (Initially Hidden) -->
                    <form action="set_core.php" method="POST" id="typeForm" style="display: none; margin-top: 10px;" class="mb-4">
                        <div class="d-flex">
                            <div class="input-field ">
                                <label for="rate">Change Rider Type</label>
                                <select name="riderType" id="riderType" class="mt-2">
                                    <option value="cyclist">Cyclist</option>
                                    <option value="biker">Biker</option>
                                </select>
                            </div>
                            <button class="btn btn-primary mx-3" name="set_riderType_btn" id="setRightType_btn" type="submit">Update</button>
                        </div>                      
                    </form>


                    <!-- default rate Default Rate Row -->
                    <div class="d-flex justify-content-between align-item-center mb-1">
                        <h5>Default Rate: <?= $userData['defaultRate'] ?> Taka</h5>
                        <button class="btn btn-sm btn-primary" id="changeRateBtn">Change</button>
                    </div>

                    <!-- Form (Initially Hidden) -->
                    <form action="set_core.php" method="POST" id="rateForm" style="display: none; margin-top: 10px;" class="mb-4">
                        <div class="d-flex">
                            <div class="input-field">
                                <label for="rate">Set Default Rate</label>
                                <input 
                                    type="number" 
                                    value="<?= $userData['defaultRate'] ?>" 
                                    class="mt-1 text-center"
                                    id="rate" 
                                    name="rate" 
                                    required 
                                    placeholder="Rate" 
                                    min="20"
                                    max="40"
                                />
                            </div>
                            <button class="btn btn-primary mx-3" name="setDefaultRate_btn" id="setDefaultRate_btn" type="submit">Update</button>
                        </div>                      
                    </form>

                    <!-- default rate Default Rate Row -->
                    <div class="d-flex justify-content-between align-item-center mb-1">
                        <h5>Besic Salary: <?= $userData['basic_salary'] ?> Taka</h5>
                        <button class="btn btn-sm btn-primary" id="changeSalaryBtn">Change</button>
                    </div>

                    <!-- Form (Initially Hidden) -->
                    <form action="set_core.php" method="POST" id="salaryForm" style="display: none; margin-top: 10px;" class="mb-1">
                        <div class="d-flex">
                            <div class="input-field">
                                <label for="salary">Set Besic Salary</label>
                                <input 
                                    type="number" 
                                    value="<?= $userData['basic_salary'] ?>" 
                                    class="mt-1 text-center"
                                    id="salary" 
                                    name="salary" 
                                    required 
                                    placeholder="Salary" 
                                    min="3000"
                                    max="8000"
                                />
                            </div>
                            <button class="btn btn-primary mx-3" name="setBesicSalary_btn" id="setBesicSalary_btn" type="submit">Update</button>
                        </div>                      
                    </form>
                    
                    <div class="d-flex justify-content-between">
                        <h5>Current Version: <span style='color:green'>version-2.05_updated</span> </h5>  
                        <h4>Last Updated: <span style='color:green'>1 July 2025</span> </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to toggle the form visibility
    document.getElementById('changeRateBtn').addEventListener('click', function () {
        const form = document.getElementById('rateForm');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
    });

    document.getElementById('changeRiderType').addEventListener('click', function () {
        const form = document.getElementById('typeForm');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
    });

    document.getElementById('changeSalaryBtn').addEventListener('click', function () {
        const form = document.getElementById('salaryForm');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
    });
</script>

<?php include('../includes/footer.php'); ?>





