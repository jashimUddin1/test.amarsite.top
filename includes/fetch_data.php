<?php // fetch_data.php
include("db/dbcon.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ✅ Determine user id to view
$user_id = $_SESSION['auth_user']['id'];
$user_role = $_SESSION['auth_user']['role'] ?? 'user';

$view_user_id = $user_id; // default: own data
if ($user_role === 'admin' && isset($_GET['user_id'])) {
    $view_user_id = intval($_GET['user_id']); // override if admin
}

// ✅ Month ordering
$monthDESC = "'December', 'November', 'October', 'September', 'August', 'July', 'June', 'May', 'April', 'March', 'February', 'January'";

// ✅ Latest month/year fallback
$sqlLatest = "SELECT `year`, `month` FROM `month` WHERE user_id=? ORDER BY `year` DESC, FIELD(`month`, $monthDESC) LIMIT 1";
$stmtLatest = $con->prepare($sqlLatest);
$stmtLatest->bind_param("i", $view_user_id);
$stmtLatest->execute();
$resultLatest = $stmtLatest->get_result();

$latestYear = null;
$latestMonth = null;
if ($resultLatest->num_rows > 0) {
    $rowLatest = $resultLatest->fetch_assoc();
    $latestYear = $rowLatest['year'];
    $latestMonth = $rowLatest['month'];
}
$stmtLatest->close();

// ✅ Use GET or fallback to latest
$year = isset($_GET['year']) ? $_GET['year'] : $latestYear;
$month = isset($_GET['month']) ? $_GET['month'] : $latestMonth;

// ✅ Fetch actual data
$sqlFetch = "SELECT * FROM `data` WHERE `year` = ? AND `month` = ? AND user_id=? ORDER BY `date` ASC";
$stmtFetch = $con->prepare($sqlFetch);
$stmtFetch->bind_param("ssi", $year, $month, $view_user_id);
$stmtFetch->execute();
$fetch_result = $stmtFetch->get_result();

// ✅ Display
echo "<div class='container selectTable'>Selected Month: <span>$month-$year</span></div>";
echo "<table id='dataTable' class='table table-bordered table-striped'>
        <thead>
            <tr class='monthYear'>
                <th colspan='8'>$month $year</th>
            </tr>
            <tr class='tableHeading'>
                <th>Date</th>
                <th>Received</th>
                <th><span class='full-text'>Cancel</span><span class='short-text'>Can</span></th>
                <th><span class='full-text'>Reschedule</span><span class='short-text'>Res</span></th>
                <th><span class='full-text'>Delivered</span><span class='short-text'>Del</span></th>
                <th>Rate</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody id='data-table-body'>";

if ($fetch_result->num_rows > 0) {
    while ($row = $fetch_result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['date']}</td>
                <td>{$row['received']}</td>
                <td>{$row['cancel']}</td>
                <td>{$row['reschedule']}</td>
                <td>{$row['delivered']}</td>
                <td>{$row['rate']}</td>
                <td>{$row['total']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No data available for $month-$year</td></tr>";
}

echo "</tbody></table>";
$con->close();
?>
<script>
function confirmDelete() {
    return confirm("Are you sure?");
}
</script>
