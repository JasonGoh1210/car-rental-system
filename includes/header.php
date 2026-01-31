<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Rental System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Basic CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<nav style="padding:10px; background:#333;">
    <a href="/user/index.php" style="color:white; margin-right:15px;">Home</a>
    <a href="/user/cars.php" style="color:white; margin-right:15px;">Cars</a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/user/dashboard.php" style="color:white; margin-right:15px;">Dashboard</a>
        <a href="/user/logout.php" style="color:white;">Logout</a>
    <?php else: ?>
        <a href="/user/login.php" style="color:white; margin-right:15px;">Login</a>
        <a href="/user/register.php" style="color:white;">Register</a>
    <?php endif; ?>
</nav>

<div style="padding:20px;">
