<?php
require_once '../includes/config.php';
include '../includes/header.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM car WHERE Car_ID=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();
?>

<div class="car-details">
    <h2><?= $car['Car_Name']; ?></h2>
    <div class="price">RM <?= $car['Car_Price']; ?> / day</div>
    <p><?= $car['Car_Description']; ?></p>
    <p>Available Stock: <?= $car['Car_Stock']; ?></p>

    <a href="car_catalogue.php" class="btn">Back</a>
</div>

<?php include '../includes/footer.php'; ?>