<?php
session_start();
include '../config/db.php';

/* Admin access protection */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../user/login.php");
    exit();
}

/* Auto complete booking when return date passed */
date_default_timezone_set('Asia/Kuala_Lumpur');

$autoCompleteSql = "
    SELECT booking_id, car_id
    FROM bookings
    WHERE booking_status = 'pending'
      AND DATE(end_date) < CURDATE()
";

$resultAuto = $conn->query($autoCompleteSql);

if ($resultAuto && $resultAuto->num_rows > 0) {
    while ($rowAuto = $resultAuto->fetch_assoc()) {

        $bookingId = $rowAuto['booking_id'];
        $carId     = $rowAuto['car_id'];

        $conn->query("
            UPDATE bookings
            SET booking_status = 'completed'
            WHERE booking_id = $bookingId
        ");

        $conn->query("
            UPDATE cars
            SET status = 'available'
            WHERE car_id = $carId
        ");
    }
}

/* Handle booking filter */
$filter = $_GET['filter'] ?? '';
$where  = "";

if ($filter === 'today_rent') {
    $where = "WHERE DATE(b.start_date) = CURDATE()";
} elseif ($filter === 'today_return') {
    $where = "WHERE DATE(b.end_date) = CURDATE()";
}

/* Retrieve booking records */
$sql = "
    SELECT 
        b.booking_id,
        u.name AS username,
        c.car_brand,
        c.car_name,
        b.start_date,
        b.end_date,
        b.total_price,
        b.booking_status,
        b.created_at
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN cars c ON b.car_id = c.car_id
    $where
    ORDER BY b.created_at DESC
";

$result = $conn->query($sql);

/* Badge helper */
function badge($status)
{
    return match ($status) {
        'pending'   => 'warning',
        'approved'  => 'info',
        'completed' => 'success',
        'cancelled' => 'danger',
        default     => 'secondary'
    };
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

<body>

<div class="d-flex">

<?php include '../admin/sidebar.php'; ?>

<div class="flex-grow-1 p-4">

<h3>Manage Bookings</h3>

<table class="table table-hover align-middle">
<thead class="table-light text-center">
<tr>
    <th>#</th>
    <th>User</th>
    <th>Car</th>
    <th>Start</th>
    <th>Return</th>
    <th>Total</th>
    <th>Status</th>
    <th>Created</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php if ($result && $result->num_rows > 0): ?>
<?php $i = 1; ?>
<?php while ($row = $result->fetch_assoc()): ?>
<tr class="text-center">

<td><?= $i++ ?></td>
<td><?= htmlspecialchars($row['username']) ?></td>
<td><?= htmlspecialchars($row['car_brand'].' '.$row['car_name']) ?></td>
<td><?= date('d M Y', strtotime($row['start_date'])) ?></td>
<td><?= date('d M Y', strtotime($row['end_date'])) ?></td>
<td><?= number_format($row['total_price'], 2) ?></td>

<td>
    <span class="badge bg-<?= badge($row['booking_status']) ?>">
        <?= ucfirst($row['booking_status']) ?>
    </span>
</td>

<td><?= date('d M Y', strtotime($row['created_at'])) ?></td>

<td>
    <a href="booking_detail.php?id=<?= $row['booking_id'] ?>"
       class="btn btn-primary btn-sm">
        Detail
    </a>

    <?php if ($row['booking_status'] === 'pending'): ?>
        <a href="booking_cancel.php?id=<?= $row['booking_id'] ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Cancel this booking?')">
            Cancel
        </a>
    <?php endif; ?>
</td>

</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="9" class="text-center text-muted">No bookings found</td>
</tr>
<?php endif; ?>
</tbody>

</table>

</div>
</div>

</body>
</html>
