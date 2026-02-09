<?php
session_start();
include '../config/db.php';

/* Admin access protection */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../user/login.php");
    exit();
}

/* Fetch all users */
$result = $conn->query("
    SELECT user_id, name, email, phone, role, status, created_at
    FROM users
    ORDER BY user_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>

    <!-- Main stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

<body>

<div class="d-flex">

    <!-- Sidebar -->
    <?php include '../admin/sidebar.php'; ?>

    <!-- Main content -->
    <div class="flex-grow-1 p-4">

        <!-- Page title -->
        <h3>Manage Users</h3>
        <p class="text-muted">View and manage system users</p>

        <!-- User table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">

                <table class="table table-hover mb-0 text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $i = 1; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>

                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['phone']) ?></td>

                                <!-- User role -->
                                <td>
                                    <span class="badge <?= $row['role'] === 'admin'
                                        ? 'bg-danger'
                                        : 'bg-secondary' ?>">
                                        <?= ucfirst($row['role']) ?>
                                    </span>
                                </td>

                                <!-- User status -->
                                <td>
                                    <?php if ($row['status'] === 'blacklisted'): ?>
                                        <span class="badge bg-dark">Blacklisted</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= $row['created_at'] ?></td>

                                <!-- Action buttons -->
                                <td>
                                    <?php if ($row['role'] !== 'admin'): ?>

                                        <?php if ($row['status'] === 'active'): ?>
                                            <a href="toggle_user_status.php?id=<?= $row['user_id'] ?>&action=blacklist"
                                               class="btn btn-sm btn-dark"
                                               onclick="return confirm('Blacklist this user?')">
                                                Blacklist
                                            </a>
                                        <?php else: ?>
                                            <a href="toggle_user_status.php?id=<?= $row['user_id'] ?>&action=activate"
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('Activate this user?')">
                                                Activate
                                            </a>
                                        <?php endif; ?>

                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-muted py-3">
                                No users found
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>

    </div>
</div>

</body>
</html>
