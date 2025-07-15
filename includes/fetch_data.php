


<?php //fetch_data.php
include("db/dbcon.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$monthDESC = "'December', 'November', 'October', 'September', 'August', 'July', 'June', 'May', 'April', 'March', 'February', 'January'";

$sqlLatest = "SELECT `year`, `month` FROM `month` WHERE user_id='$user_id' ORDER BY `year` DESC, FIELD(`month`, $monthDESC) LIMIT 1";
$resultLatest = $con->query($sqlLatest);
$latestYear = null;
$latestMonth = null;

if ($resultLatest->num_rows > 0) {
    $rowLatest = $resultLatest->fetch_assoc();
    $latestYear = $rowLatest['year'];
    $latestMonth = $rowLatest['month'];
}

$year = isset($_GET['year']) ? $_GET['year'] : $latestYear;
$month = isset($_GET['month']) ? $_GET['month'] : $latestMonth;

$sqlFetch = "SELECT * FROM `data` WHERE `year` = '$year' AND `month` = '$month' AND user_id='$user_id' ORDER BY `date` ASC";
$fetch_result = $con->query($sqlFetch);

echo"<div class='container selectTable'>Selected Month: <span>$month-$year</span></div>";
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
        $row_id = $row['id'];
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
    echo "<tr><td colspan='6' class='text-center'>No data available for $month-$year</td></tr>";
}

echo "</tbody></table>";
$con->close();
?>
<script>
function confirmDelete() {
    return confirm("Are you sure?");
}
</script>
