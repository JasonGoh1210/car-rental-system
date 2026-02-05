<?php
require_once '../includes/config.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <div class="header">
        <h1>Manage Category</h1>
    </div>

    <div class="content">
        <div class="action-bar">
            <a href="AddCategory.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Category
            </a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $result = $conn->query("SELECT * FROM category");
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?= $row['Category_ID']; ?></td>
                    <td><?= $row['Category_Name']; ?></td>
                    <td><?= $row['status']; ?></td>
                    <td>
                        <a href="EditCategory.php?id=<?= $row['Category_ID']; ?>">Edit</a> |
                        <a href="DeleteCategory.php?id=<?= $row['Category_ID']; ?>"
                           onclick="return confirm('Delete this category?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>