<?php
session_start();

include "../config/db.php";
include "../include/header.php";

/* Login check: redirect to login page if user not logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* Get POST data */
$car_id              = $_POST['car_id'] ?? '';
$start_date          = $_POST['start_date'] ?? $_POST['start_datetime'] ?? '';
$end_date            = $_POST['end_date'] ?? $_POST['end_datetime'] ?? '';
$total_price         = $_POST['total_price'] ?? '';
$pickup_type         = $_POST['pickup_type'] ?? '';
$delivery_location   = $_POST['delivery_location'] ?? '';

/* Validate booking data to prevent invalid submission */
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    (empty($car_id) || empty($start_date) || empty($end_date))
) {
    echo "<div class='container mt-5 alert alert-danger'>Invalid booking data.</div>";
    include "../include/footer.php";
    exit;
}
/* Check overlapping bookings (Prepared Statement) */
$sql = "
    SELECT 1
    FROM bookings
    WHERE car_id = ?
      AND booking_status IN ('pending', 'confirmed')
      AND NOT (
            end_date <= ?
            OR start_date >= ?
      )
    LIMIT 1
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

/*
    i = integer (car_id)
    s = string (datetime)
*/
mysqli_stmt_bind_param(
    $stmt,
    "iss",
    $car_id,
    $start_date,
    $end_date
);

mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo "
        <script>
            alert('This car is not available for the selected dates.');
            window.history.back();
        </script>
        ";
    exit;

}

mysqli_stmt_close($stmt);


/* Get selected car details */
$sql = "
    SELECT c.*, cat.category_name
    FROM cars c
    LEFT JOIN car_categories cat ON c.category_id = cat.category_id
    WHERE c.car_id = '$car_id'
";
$result = mysqli_query($conn, $sql);
$car    = mysqli_fetch_assoc($result);

/* Handle car not found */
if (!$car) {
    echo "<div class='container mt-5 alert alert-danger'>Car not found.</div>";
    include "../include/footer.php";
    exit;
}
?>

<div class="container py-5 mt-4">

    <h3 class="mb-4 fw-bold">Booking Confirmation</h3>

    <!-- Car Summary Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5><?= $car['car_brand'] ?> <?= $car['car_name'] ?></h5>
            <p class="text-muted mb-1"><?= $car['category_name'] ?></p>

            <p class="mb-1">
                📅 <strong>Rental Period:</strong><br>
                <?= date('d M Y, h:i A', strtotime($start_date)) ?>
                –
                <?= date('d M Y, h:i A', strtotime($end_date)) ?>
            </p>

            <p class="mb-1">
                🚗 <strong>Pickup Method:</strong>
                <?= ucfirst($pickup_type) ?>
            </p>

            <?php if ($pickup_type === 'delivery'): ?>
                <p class="mb-1">
                    📍 <strong>Delivery Location:</strong><br>
                    <?= htmlspecialchars($delivery_location ?: '-') ?>
                </p>
            <?php endif; ?>

            <p class="fw-bold text-success mt-2">
                Total Payable: RM <?= number_format($total_price, 2) ?>
            </p>

        </div>
    </div>

    <!-- Driver Information Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="mb-3">Driver Information</h5>

            <form method="POST" action="payment.php">

                <div class="mb-3">
                    <label class="form-label">Driver Name</label>
                    <input type="text" name="driver_name" class="form-control" required>
                </div>

                <div class="mb-3"> 
                <label class="form-label">IC / Passport No</label> 
                <input
                    type="text"
                    name="driver_ic"
                    class="form-control"
                    pattern="[0-9]{12}"
                    maxlength="12"
                    placeholder="12-digit IC number"
                    required
                >
                </div>


                <div class="mb-3">
                    <label class="form-label">Driving License No</label>
                    <input
                        type="text"
                        name="license_no"
                        id="license_no"
                        class="form-control"
                        placeholder="e.g. 1234567AB12C3D4"
                        pattern="^[0-9]{7}[A-Za-z0-9]{8}$"
                        required
                    >
                    <small class="text-muted">
                        First 7 digits must be numbers, followed by 8 alphanumeric characters
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Driver Home Address</label>
                    <textarea
                        name="driver_address"
                        class="form-control"
                        rows="3"
                        placeholder="Enter driver's residential address"
                        required
                    ></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input
                        type="text"
                        name="contact_no"
                        class="form-control"
                        value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                        pattern="^[0-9]{10}"
                        placeholder="e.g. 0123456789"
                        required
                    >
                </div>

                <!-- Hidden booking data -->
                <input type="hidden" name="car_id" value="<?= $car_id ?>">
                <input type="hidden" name="start_date" value="<?= $start_date ?>">
                <input type="hidden" name="end_date" value="<?= $end_date ?>">
                <input type="hidden" name="total_price" value="<?= $total_price ?>">
                <input type="hidden" name="pickup_type" value="<?= $pickup_type ?>">
                <input type="hidden" name="delivery_location" value="<?= htmlspecialchars($delivery_location) ?>">

                <button class="btn btn-success btn-lg w-100">
                    Confirm and Proceed to Payment
                </button>

            </form>

        </div>
    </div>

</div>

<?php include "../include/footer.php"; ?>
