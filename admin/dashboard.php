<?php
session_start();
include '../config/db.php';

/* Admin access protection */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../user/login.php");
    exit();
}

/* Get total number of cars */
$totalCars = $conn->query("
    SELECT COUNT(*) AS total 
    FROM cars
")->fetch_assoc()['total'] ?? 0;

/* Get total number of bookings */
$totalBooking = $conn->query("
    SELECT COUNT(*) AS total 
    FROM bookings
")->fetch_assoc()['total'] ?? 0;

/* Get active rentals based on current date */
$activeRental = $conn->query("
    SELECT COUNT(*) AS total
    FROM bookings
    WHERE start_date <= NOW()
      AND end_date > NOW()
")->fetch_assoc()['total'] ?? 0;

/* Get total number of users (exclude admin) */
$totalUser = $conn->query("
    SELECT COUNT(*) AS total
    FROM users
    WHERE role = 'user'
")->fetch_assoc()['total'] ?? 0;

/* Initialize booking statistics */
$bookingStats = [
    'pending'   => 0,
    'approved'  => 0,
    'cancelled' => 0
];

/* Count bookings by status */
$q1 = $conn->query("
    SELECT booking_status, COUNT(*) AS total
    FROM bookings
    GROUP BY booking_status
");

/* Store booking statistics into array */
while ($row = $q1->fetch_assoc()) {
    $bookingStats[$row['booking_status']] = $row['total'];
}

/* Prepare data for car category chart */
$categoryLabels = [];
$categoryData   = [];

/* Count cars for each category */
$q2 = $conn->query("
    SELECT cat.category_name, COUNT(c.car_id) AS total
    FROM car_categories cat
    LEFT JOIN cars c ON cat.category_id = c.category_id
    GROUP BY cat.category_id
");

/* Store category names and totals */
while ($row = $q2->fetch_assoc()) {
    $categoryLabels[] = $row['category_name'];
    $categoryData[]   = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <!-- Main stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

<body>

<div class="d-flex">

    <!-- Sidebar navigation -->
    <?php include '../admin/sidebar.php'; ?>

    <!-- Main dashboard content -->
    <div class="flex-grow-1 p-4">

        <!-- Page title -->
        <h3 class="mb-4">Dashboard</h3>

        <!-- Statistic summary cards -->
        <div class="row g-3 mb-4">

            <!-- Total cars -->
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Total Cars</h6>
                        <h3><?= $totalCars ?></h3>
                    </div>
                </div>
            </div>

            <!-- Total bookings -->
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Total Booking</h6>
                        <h3><?= $totalBooking ?></h3>
                    </div>
                </div>
            </div>

            <!-- Active rentals -->
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Active Rental</h6>
                        <h3><?= $activeRental ?></h3>
                    </div>
                </div>
            </div>

            <!-- Total users -->
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Total User</h6>
                        <h3><?= $totalUser ?></h3>
                    </div>
                </div>
            </div>

        </div>

        <!-- Charts section -->
        <div class="row g-3 mt-2">

            <!-- Booking overview chart -->
            <div class="col-md-7">
                <div class="card p-3 h-100">
                    <h6>Booking Overview</h6>

                    <!-- Fixed chart height -->
                    <div style="height:300px;">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Car category distribution chart -->
            <div class="col-md-5">
                <div class="card p-3 h-100">
                    <h6>Car Categories</h6>

                    <!-- Centered chart with fixed height -->
                    <div style="height:300px;"
                         class="d-flex justify-content-center align-items-center">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* Booking overview bar chart */
new Chart(document.getElementById('bookingChart'), {
    type: 'bar',
    data: {
        labels: ['Pending', 'Approved', 'Cancelled'],
        datasets: [{
            label: 'Total Bookings',
            data: [
                <?= $bookingStats['pending'] ?>,
                <?= $bookingStats['approved'] ?>,
                <?= $bookingStats['cancelled'] ?>
            ],
            backgroundColor: ['#ffc107', '#198754', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        }
    }
});

/* Car categories pie chart */
new Chart(document.getElementById('categoryChart'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($categoryLabels) ?>,
        datasets: [{
            data: <?= json_encode($categoryData) ?>,
            backgroundColor: [
                '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1'
            ]
        }]
    },
    options: {
        responsive: true
    }
});
</script>

</body>
</html>
