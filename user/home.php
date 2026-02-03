<?php
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

    <!-- Custom CSS -->
    <link rel="stylesheet" href="asset/style.css">

    <style>
        /* ===== GLASS NAVBAR STYLE ===== */
        .glass-navbar {
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .navbar-brand img {
            height: 40px;
            width: auto;
            margin-right: 10px;
        }

        .navbar-nav .nav-link {
            color: #ddd;
            margin: 0 10px;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #fff;
        }
    </style>
</head>

<body>

<!-- 🔹 NAVBAR -->
<nav class="navbar navbar-expand-lg glass-navbar fixed-top">
    <div class="container">

        <!-- 🔹 LOGO + IMAGE -->
        <a class="navbar-brand d-flex align-items-center" href="/car-rental-system/user/home.php">
            <!-- 你的 Logo 图片 -->
            <img src="assets/images/logo.png" alt="Logo">
            <span class="text-white fw-bold">Wheel Rentals</span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navMenu">

            <!-- 🔹 CENTER MENU -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="/car-rental-system/user/home.php">Home</a>
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

        </div>

        <!-- 🔹 RIGHT MENU -->
        <ul class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <span class="nav-link text-light">
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
</nav>
