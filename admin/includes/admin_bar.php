
<!-- admin_bar.php -->
<div class="bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">Developer Jasim</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav text-center ms-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="../index.php">Home</a>
                                </li>

                                <!-- Dynamic Years and Months Dropdown -->
                                <li class="nav-item dropdown">
                                    <ul class="navbar-nav ms-auto">
                                        <?php      

                                        $monthOrder = "'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'";
                                        
                                        // Fetch years and months
                                        $sql = "SELECT `year`, `month` 
                                                FROM `month` 
                                                WHERE user_id='$user_id' 
                                                ORDER BY `year` ASC, FIELD(`month`, $monthOrder)";
                                  
                                        $result = $con->query($sql);

                                        $yearMonthData = [];
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $year = htmlspecialchars($row['year']);
                                                $month = htmlspecialchars($row['month']);
                                                $yearMonthData[$year][] = $month;
                                            }
                                        }

                                        // Generate nested dropdown
                                        foreach ($yearMonthData as $year => $months) {
                                            echo '<li class="nav-item dropdown">';
                                            echo '<a class="nav-link dropdown-toggle" href="#" id="dropdown' . $year . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . $year . '</a>';
                                            if (!empty($months)) {
                                                echo '<ul class="dropdown-menu" aria-labelledby="dropdown' . $year . '">';
                                                foreach ($months as $month) {
                                                    echo '<li><a class="dropdown-item" href="index.php?year=' . $year . '&month=' . $month . '">' . $month . '</a></li>';
                                                }
                                                // Add "Add New" option
                                                echo '<li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#createTableModal">Add Month</a></li>';
                                            }
                                            echo '</ul>';
                                            echo '</li>';
                                        }
                                        ?>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="../profile/profile.php">Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-danger" href="../login/logout.php">Logout</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="createTableModal" tabindex="-1" aria-labelledby="createTableModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createTableModalLabel">Add Month</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php $currentMonth = date('F'); ?>
        <form action="add_month.php" method="POST" id="addMonthForm">
          <input type="hidden" name="selected_year" id="selectedYear">
          
          <div class="mb-3">
            <label for="custom_month" class="form-label">Select Month (optional):</label>
            <select class="form-select" name="custom_month" id="custom_month">
              <option value="">-- Current Month (<?php echo $currentMonth; ?>) --</option>
              <?php
              $months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
              ];
              foreach ($months as $month) {
                  echo "<option value=\"$month\">$month</option>";
              }
              ?>
            </select>
          </div>

          <button type="submit" name="add_month_btn" class="btn btn-primary">Add Month</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.dropdown-toggle').forEach(function(el) {
    el.addEventListener('click', function() {
        const year = this.textContent.trim();
        document.getElementById('selectedYear').value = year;
    });
});

document.getElementById('addMonthForm').addEventListener('submit', function(e) {
    const selectedMonth = document.getElementById('custom_month').value;
    const currentMonth = "<?php echo $currentMonth; ?>";
    const finalMonth = selectedMonth ? selectedMonth : currentMonth;

    const confirmAdd = confirm("Are you sure you want to add the month: " + finalMonth + "?\nOnce added, you cannot delete it.");

    if (!confirmAdd) {
        e.preventDefault(); // prevent form submit
    }
});
</script>
