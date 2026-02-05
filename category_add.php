<?php
require_once '../includes/config.php';

$nameError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = trim($_POST['category_name']);

    // Check duplicate category name
    $checkQuery = "SELECT Category_ID FROM category WHERE Category_Name = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('s', $categoryName);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $nameError = 'Category already exists.';
    } else {
        $query = "INSERT INTO category (Category_Name, status) VALUES (?, 'Active')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $categoryName);
        $stmt->execute();

        header("Location: Category.php?message=success");
        exit();
    }
}
?>