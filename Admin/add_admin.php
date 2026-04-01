<?php
session_start();
require_once "../includes/db.php";
// if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

$error_msg = "";
if (isset($_POST['save_admin'])) {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    
    if ($check->rowCount() > 0) {
        $error_msg = "This email is already registered!";
    } else {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
        if ($stmt->execute([$name, $email, $hashed_pass])) {
            header("Location: admins.php");
            exit();
        }
    }
}
require_once "sidebar.php";
?>

<main class="add-product-page">
    <div class="page-header">
        <h2><i class="fas fa-user-plus"></i> Add New Admin</h2>
        <a href="admins.php" class="back-btn" style="padding:10px 20px ;color:white;border-radius:10px"><i
                class="fas fa-arrow-left"></i>
            Back to
            Admins</a>
    </div>

    <div class="form-container">
        <?php if ($error_msg): ?>
        <div
            style="background: #f8d7da; color: #842029; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 5px solid #dc3545;">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error_msg; ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-row">
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Enter admin name" required>
                </div>
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="admin@example.com" required>
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Set a strong password" required>
                </div>
                <div class="input-group">
                    <label>Role Status</label>
                    <input type="text" value="System Administrator" disabled
                        style="background: #eee; cursor: not-allowed;">
                </div>
            </div>

            <button type="submit" name="save_admin" class="submit-btn">
                <i class="fas fa-check-circle"></i> Create Admin Account
            </button>
        </form>
    </div>
</main>