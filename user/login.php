<?php
// Start session for login state & messages
session_start();

// Database connection
include '../config/db.php';

// Message variables
$error   = "";
$success = "";

/* Display register success message (from register page) */
if (isset($_SESSION['register_success'])) {
    $success = $_SESSION['register_success'];
    unset($_SESSION['register_success']); // clear after display
}

/* If already logged in, redirect to home page */
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

/* Handle login form submission */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get login input (name or email) and password
    $login_input = trim($_POST['login']);
    $password    = $_POST['password'];

    // Basic validation
    if ($login_input === "" || $password === "") {
        $error = "Please fill in all fields.";
    } else {

        // Fetch user by email OR name
        $sql = "SELECT * FROM users WHERE email = ? OR name = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $login_input, $login_input);
        $stmt->execute();
        $result = $stmt->get_result();

        // User found
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if ($user['status'] === 'blacklisted') {
                $error = "Your account has been suspended. Please contact admin.";
            } 
            // Only verify password if not blacklisted
            else if (password_verify($password, $user['password'])) {

                // Store user data in session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name']    = $user['name'];
                $_SESSION['role']    = $user['role'];

                // Redirect back to protected page (non-admin)
                if ($user['role'] !== 'admin' && isset($_SESSION['redirect_after_login'])) {
                    $redirect = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    header("Location: $redirect");
                    exit;
                }

                // Admin goes to admin dashboard
                if ($user['role'] === 'admin') {
                    header("Location: ../admin/dashboard.php");
                } 
                // Normal user goes to home
                else {
                    header("Location: home.php");
                }
                exit();

            } else {
                // Wrong password
                $error = "Incorrect name/email or password.";
            }

        } else {
            // User not found
            $error = "Incorrect name/email or password.";
        }
    }
}

/* Page styling class */
$bodyClass = "login-bg";

// Include common header
include '../include/header.php';
?>

<div class="container d-flex justify-content-center align-items-center"
     style="min-height: calc(100vh - 60px);">

    <div class="login-card">

        <!-- Page title -->
        <div class="text-center mb-4">
            <h3 class="fw-bold mb-1">Login</h3>
            <small class="text-muted">Login to your account</small>
        </div>

        <!-- Success message -->
        <?php if ($success): ?>
            <div class="alert alert-success text-center py-2">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <!-- Error message -->
        <?php if ($error): ?>
            <div class="alert alert-danger text-center py-2">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- Login form -->
        <form method="POST">

            <!-- Name or email input -->
            <div class="mb-3">
                <label class="form-label">Name or Email Address</label>
                <input type="text"
                       name="login"
                       class="form-control"
                       placeholder="e.g. xxxx@gmail.com"
                       required>
            </div>

            <!-- Password input -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       required>
            </div>

            <!-- Submit button -->
            <button type="submit"
                    class="btn btn-dark w-100 py-2 fw-bold"
                    style="background-color:#0b132b;border:none;">
                LOGIN
            </button>
        </form>

        <!-- Forgot password -->
        <div class="text-center mt-2">
            <a href="forgot_password.php" class="small">Forgot password?</a>
        </div>

        <!-- Register link -->
        <div class="text-center mt-3">
            <span class="text-muted">Create new account?</span>
            <a href="register.php">Register</a>
        </div>

    </div>
</div>

<?php
// Include common footer
include '../include/footer.php';
?>

