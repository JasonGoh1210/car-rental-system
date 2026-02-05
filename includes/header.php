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

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">

        <!-- 左：Logo -->
        <a class="navbar-brand d-flex align-items-center" href="home.php">
            <img src="assets/images/logo.png" height="30" class="me-2">
            <strong>Wheel Rentals</strong>
        </a>

        <!-- 手机 toggle -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- 中间 + 右 -->
        <div class="collapse navbar-collapse" id="mainNavbar">

            <!-- 中间菜单 -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="carlist.php">Car List</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="my_bookings.php">My Booking</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="about.php">About Us</a>
                </li>
            </ul>


            <!-- 右边 Login -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="login.php">Login</a>
                </li>
            </ul>

        </div>
    </div>
</nav>
