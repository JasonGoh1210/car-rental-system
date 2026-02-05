<?php require_once '../includes/config.php'; ?>
<?php include '../includes/header.php'; ?>

<h2>Car Catalogue</h2>

<div class="car-list">
<?php
$result = $conn->query("SELECT * FROM car WHERE status='Active'");
while ($row = $result->fetch_assoc()):
?>
    <div class="car-card">
        <h3><?= $row['Car_Name']; ?></h3>
        <p>RM <?= $row['Car_Price']; ?> / day</p>
        <a href="car_details.php?id=<?= $row['Car_ID']; ?>">View Details</a>
    </div>
<?php endwhile; ?>
</div>

<?php include '../includes/footer.php'; ?>
