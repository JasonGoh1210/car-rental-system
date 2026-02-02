<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wheel Rentals</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional custom CSS -->
    <link rel="stylesheet" href="/car-rental-system/assets/css/style.css">
</head>

<body>

<!-- 🔹 NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand" href="/car-rental-system/user/home.php">
            Wheel Rentals
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">

            <!-- 🔹 LEFT MENU -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/car-rental-system/user/home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/car-rental-system/user/cars.php">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/car-rental-system/user/booking.php">Book Now</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/car-rental-system/user/my_bookings.php">My Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/car-rental-system/user/about.php">About Us</a>
                </li>
            </ul>

            <!-- 🔹 RIGHT MENU -->
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <span class="navbar-text text-light me-3">
                            Hi, <?= htmlspecialchars($_SESSION['username']) ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/car-rental-system/user/logout.php">
                            Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/car-rental-system/user/login.php">
                            Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>
