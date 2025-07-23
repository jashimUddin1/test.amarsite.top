


<?php 
  session_start();
  include("db/dbcon.php");

  if (!isset($_SESSION['authenticated'])) {
      header("location: login/index.php");
      exit();
  }

  $user_id = $_SESSION["auth_user"]["id"];

  $monthOrder = "'January','February','March','April','May','June','July','August','September','October','November','December'";
  $monthDESC = "'December','November','October','September','August','July','June','May','April','March','February','January'";

  // Month-Year Data Grouped
  $yearMonthData = [];
  $stmt = $con->prepare("SELECT year, month FROM month WHERE user_id = ? ORDER BY year ASC, FIELD(month, $monthOrder)");
  $stmt->bind_param("i", $view_user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
      $year = htmlspecialchars($row['year']);
      $month = htmlspecialchars($row['month']);
      $yearMonthData[$year][] = $month;
  }
  $stmt->close();

  // Latest year & month with day (combined)
  $latestYear = $latestMonth = $month_day = null;
  $stmtLatest = $con->prepare("SELECT year, month, day FROM month WHERE user_id = ? ORDER BY year DESC, FIELD(month, $monthDESC) LIMIT 1");
  $stmtLatest->bind_param("i", $view_user_id);
  $stmtLatest->execute();
  $resultLatest = $stmtLatest->get_result();
  if ($row = $resultLatest->fetch_assoc()) {
      $latestYear = $row['year'];
      $latestMonth = $row['month'];
      $month_day = htmlspecialchars($row['day']);
  }
  $stmtLatest->close();

  // GET override fallback
  $year = isset($_GET['year']) ? $_GET['year'] : $latestYear;
  $month = isset($_GET['month']) ? $_GET['month'] : $latestMonth;

  // Fetch day count for selected month/year
  $stmtDay = $con->prepare("SELECT day FROM month WHERE user_id = ? AND month = ? AND year = ? LIMIT 1");
  $stmtDay->bind_param("iss", $view_user_id, $month, $year);
  $stmtDay->execute();
  $resultDay = $stmtDay->get_result();
  if ($row = $resultDay->fetch_assoc()) {
      $month_day = htmlspecialchars($row['day']);
  }
  $stmtDay->close();

  // User info
  $stmtUser = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
  $stmtUser->bind_param("i", $view_user_id);
  $stmtUser->execute();
  $resultUser = $stmtUser->get_result();
  $user_data = $resultUser->fetch_assoc();
  $stmtUser->close();

  $besic_salary = htmlspecialchars($user_data['basic_salary']);
  $rider_type = htmlspecialchars($user_data['riderType']);
  $oil_cost = htmlspecialchars($user_data['oil_cost']);
  $user_role = htmlspecialchars($user_data['role']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="assets/css/styles.css" rel="stylesheet" type="text/css">
</head>
<body>

 
<div class="headerWrapper">
  <div class="header">
    <div class="headerLogo">
        <a href="#"><span class='full-text'>Developer Jasim</span><span class='short-text'>D. Jasim </span> </a>
   
    </div>
    <div class="headerMenu">
      <ul>
        <li class="nav-item dropdown">
          <div id="yearNavigator" style="display: flex; align-items: center; gap: 10px; cursor: pointer; position: relative;">
            <button id="prevYearBtn">&lt;</button>
            <span id="currentYear" class="dropbtn" style="color: white;">Year</span>
            <button id="nextYearBtn">&gt;</button>
            <ul id="monthDropdown" class="dropdown-menu dropdown-content" style="position: absolute; top: 30px; display: none; background: white; border: 1px solid #ccc; padding: 5px; z-index: 1000;"></ul>
          </div>
        </li>
        <li><a class='full-text' id="adminBtn" href="admin">Admin Panel</a> <a class='short-text' id="adminBtn" href="admin">Admin</a></li>
      </ul>
    </div>
  </div>
</div>

<?php include "includes/session.php"; ?>

<?php
  if($user_role=="admin"){
    echo $user_role ;
  }else{
    echo "Normal user";
  }
?>


<?php 
  // Admin হলে সব user show করাবো
  $userList = [];
  if ($user_role === "admin") {
      $stmtUsers = $con->prepare("SELECT id, first_name, last_name FROM users ORDER BY first_name ASC");
      $stmtUsers->execute();
      $resultUsers = $stmtUsers->get_result();
      while ($row = $resultUsers->fetch_assoc()) {
          $userList[] = $row;
      }
      $stmtUsers->close();
  }

  // যেই user select করা হয়েছে তা দেখার জন্য
  if ($user_role === "admin" && isset($_GET['user_id'])) {
      $view_user_id = intval($_GET['user_id']);
  } else {
      $view_user_id = $user_id; // Normal user হলে নিজের ID
  }
?>

<div class="container">
  <h3>
    Welcome, <?= htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']) ?> (ID: <?= $view_user_id ?>)
  </h3>

  <?php if ($user_role === 'admin'): ?>
    <form method="get" action="index.php">
      <label for="user_id">Select User:</label>
      <select name="user_id" id="user_id">
        <?php foreach ($userList as $user): ?>
          <option value="<?= $user['id'] ?>" <?= ($view_user_id == $user['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?> (ID: <?= $user['id'] ?>)
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit">Apply</button>
    </form>

    <p style="margin-top:10px;">Selected User ID: <strong><?= $view_user_id ?></strong></p>
  <?php endif; ?>
</div>





<div style="margin-top:5px;" class="tabledataWrapper">
  <?php include 'includes/fetch_data.php'; ?>
</div>

<div class="tdetailsWrapper">
  <div class="tDetails container">
    <div class="receivedTotal lgb">Received Total: <span id="rcvdTotal"> </span></div>
    <div class="deliveredTotal gw">Delivered Total: <span id="dlvrdTotal"> </span></div>
    <div class="cancelTotal awr">Cancel Total: <span id="cancelTotal"> </span></div>
    <div class="rescheduledTotal awb">Rescheduled Total: <span id="rescheduledTotal"> </span></div>
    <div class="returnedTotal awr">Returned Total: <span id="returnTotal"> </span></div>

    <div class="deliverdRate gw">Delivered Rate: <span id="dlvrdRate"></span>%</div>
    <div class="returnRate awr">Return Rate: <span id="returnRate"></span>%</div>
    <div class="possibleTotal gw">Possible Total: <span id="posiTotal"> </span>(ps)</div>
    <div class="totalDay lgb">Total Day:  <span id="totalDay"><?= $month_day ?></span></div>
    <div class="offDay awb">Off Day:  <span id="offDay"> </span></div>
    <div id="absentRow" class="awr red">Absent:  <span id="absent"> </span></div>

    <div class="totalWorkDay lgb">Working Day:  <span id="totalWrkDay"> </span></div>
    <div class="possibleWorkDay gw">Day:  <span id="possibleTotalWrkDay"> </span>(Possible)</div>
    <div class="highDel gw">Highest Delivered:  <span id="highDel"> </span></div>
    <div class="lowDel bg_royalblue ">Lowest Delivered:  <span id="lowDel"> </span></div>
    <div class="avarageDelivery lb">Average Delivery:  <span id="avarDel"> </span></div>

    <div class="avarageTk bg_aqua">Average Incentive:  <span id="avarTk"> </span></div>
    <div class="avarageTotal bg_aqua">Average Total:  <span id="avarageTotalTk"> </span> (tk)</div>
    <div class="possibleIncome bg_aqua">Possible Incentive:  <span id="possibleIncome"> </span></div>
    <div class="grossSalary bg_aqua">Fixed Salary:  <span id="grossSalary"> </span></div>

    <?php if ($rider_type == 'biker'): ?>
      <div class="possibleOilBill bg_aqua">
          Oil Bill:  <span id="possibleOilBill"><?= htmlspecialchars($user_data['oil_cost']) ?></span> (Possible)
      </div>
    <?php endif; ?>

    <div class="estimetTotal bg_aqua black">Estimated Total:  <span id="estimatedTotal"> </span></div>
    <div class="comissionTotal gw">Comission Tk: <span id="comissiontk"> </span></div>
    <div class="salary gw">Salary:  <span id="salaryTk"> </span></div>

    <?php if($rider_type == 'biker'): ?>
        <div class='oilBill gw'>Oil Bill:  <span id='oilBill'> </span></div>
    <?php endif; ?>
  </div>

  <div class="container" id="finishedSalary">
    <h1>Total Salary: <span id="salaryTotal"></span> tk</h1>
  </div>
</div>
<br>

<!-- pass data by hidden -->
<input type="hidden" class="besic_salary" value="<?= $besic_salary ?>">
<input type="hidden" class="month_day" value="<?= $month_day ?>">
<input type="hidden" class="oil_cost" value="<?= $oil_cost ?>">

<script type="text/javascript" src="index.js"></script>
<script>
  const yearMonthData = <?= json_encode($yearMonthData); ?>;

  document.addEventListener("DOMContentLoaded", () => {
    const currentYearSpan = document.getElementById("currentYear");
    const monthDropdown = document.getElementById("monthDropdown");
    const prevBtn = document.getElementById("prevYearBtn");
    const nextBtn = document.getElementById("nextYearBtn");

    const years = Object.keys(yearMonthData).sort();
    let currentIndex = years.length - 1;

    function renderYear() {
      const year = years[currentIndex];
      currentYearSpan.textContent = ` ${year} `;
      monthDropdown.innerHTML = "";

      yearMonthData[year].forEach(month => {
        const li = document.createElement("li");
        const a = document.createElement("a");
        a.href = `index.php?year=${year}&month=${month}`;
        a.className = "dropdown-item";
        a.textContent = month;
        li.appendChild(a);
        monthDropdown.appendChild(li);
      });
    }

    function toggleDropdown() {
      monthDropdown.style.display = monthDropdown.style.display === "block" ? "none" : "block";
    }

    prevBtn.addEventListener("click", () => {
      if (currentIndex > 0) {
        currentIndex--;
        renderYear();
        monthDropdown.style.display = "none";
      }
    });

    nextBtn.addEventListener("click", () => {
      if (currentIndex < years.length - 1) {
        currentIndex++;
        renderYear();
        monthDropdown.style.display = "none";
      }
    });

    currentYearSpan.addEventListener("click", toggleDropdown);
    currentYearSpan.addEventListener("mouseenter", toggleDropdown);
    monthDropdown.addEventListener("mouseleave", () => {
      monthDropdown.style.display = "none";
    });

    renderYear();
  });
</script>

</body>
</html>
