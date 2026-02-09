<?php
// Start session
session_start();

// Database connection
include '../config/db.php';

$error        = "";
$email_error  = "";
$phone_error  = "";

/*Preserve Input Values */
$name  = $_POST['name']  ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

/* Handle Form Submit*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    /* Basic validation */
    if ($name === "" || $email === "" || $phone === "" || $password === "" || $confirm === "") {
        $error = "All fields are required.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email address.";
    }
    elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    }
    elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    }
    else {

        /* Check duplicate email or phone */
        $check = $conn->prepare("
            SELECT email, phone 
            FROM users 
            WHERE email = ? OR phone = ?
        ");
        $check->bind_param("ss", $email, $phone);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                if ($row['email'] === $email) {
                    $email_error = "Email already registered.";
                }
                if ($row['phone'] === $phone) {
                    $phone_error = "Phone number already registered.";
                }
            }

        } else {

            /* Password hashing */
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            /* Insert new user */
            $stmt = $conn->prepare("
                INSERT INTO users (name, email, phone, password, role)
                VALUES (?, ?, ?, ?, 'user')
            ");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['register_success'] =
                    "Registration successful! Please login.";
                header("Location: login.php");
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

/* Page styling */
$bodyClass = "login-bg";
include '../include/header.php';
?>

<div class="login-wrapper">
    <div class="login-card">

        <h3 class="text-center fw-bold mb-1">Register</h3>
        <p class="text-center text-muted mb-4">Create a new account</p>

        <?php if ($error): ?>
            <div class="alert alert-danger text-center py-2">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <!-- Full Name -->
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text"
                       name="name"
                       class="form-control"
                        placeholder="e.g. John Doe"
                       value="<?= htmlspecialchars($name) ?>"
                       required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email"
                       name="email"
                       class="form-control <?= $email_error ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($email) ?>"
                        placeholder="e.g. user@example.com"
                       required>
                <?php if ($email_error): ?>
                    <div class="invalid-feedback">
                        <?= $email_error ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text"
                       name="phone"
                       class="form-control <?= $phone_error ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($phone) ?>"
                       placeholder="e.g. 012-3456789"
                       required>
                <?php if ($phone_error): ?>
                    <div class="invalid-feedback">
                        <?= $phone_error ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">
                    Password (minimum 8 characters)
                </label>
                <input type="password"
                       name="password"
                       class="form-control"
                       minlength="8"
                       placeholder="Enter your password"
                       required>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <input type="password"
                       name="confirm_password"
                       class="form-control"
                       minlength="8"
                       placeholder="Re-enter your password"
                       required>
            </div>

            <button type="submit"
                    class="btn w-100 py-2 fw-bold"
                    style="background-color:#0b132b;color:#fff;">
                REGISTER
            </button>
        </form>

        <div class="text-center mt-3">
            Already have an account?
            <a href="login.php">Login</a>
        </div>

    </div>
</div>

<?php include '../include/footer.php'; ?>
