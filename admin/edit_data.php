
<?php //edit_data.php
include '../db/dbcon.php';
include '../includes/header.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$tableName = "data";
$year = isset($_GET['year']) ? $_GET['year'] : '';
$month = isset($_GET['month']) ? $_GET['month'] : '';

if (isset($_GET['id'])) {
    $data_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM `$tableName` WHERE id='$data_id'";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $data = mysqli_fetch_array($query_run);
    } else {
        echo "<h4>No such ID found</h4>";
        exit;
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Data
                        <a href="index.php?year=<?= $year; ?>&month=<?= $month; ?>" class="btn btn-danger float-end">BACK</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="update_data.php" method="POST">
                        <input type="hidden" name="data_id" value="<?= $data['id']; ?>">
                        <input type="hidden" name="tableName" value="<?= htmlspecialchars($tableName); ?>">
                        <input type="hidden" name="year" value="<?= htmlspecialchars($year); ?>">
                        <input type="hidden" name="month" value="<?= htmlspecialchars($month); ?>">

                        <div class="mb-3">
                            <label>Date</label>
                            <input type="date" name="date" value="<?= $data['date']; ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Received</label>
                            <input type="text" name="received" value="<?= $data['received']; ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Cancel</label>
                            <input type="number" name="cancel" value="<?= $data['cancel']; ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Reschedule</label>
                            <input type="number" name="reschedule" value="<?= $data['reschedule']; ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Rate</label>
                            <input type="text" name="rate" value="<?= $data['rate']; ?>" class="form-control">
                        </div>

                        <div class="mb-3">
                            <button type="submit" name="update_count" class="btn btn-primary">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>