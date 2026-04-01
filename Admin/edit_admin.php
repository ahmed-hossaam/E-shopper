<?php
session_start();
require_once "../includes/db.php";

// 1. Page Protection
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) { 
    header("Location: admins.php"); 
    exit(); 
}

// Fetch admin data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'admin'");
$stmt->execute([$id]);
$admin = $stmt->fetch();

if (!$admin) { 
    header("Location: admins.php"); 
    exit(); 
}

$success_msg = "";
if (isset($_POST['update_admin'])) {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    
    // If password field is not empty, update it
    if (!empty($_POST['password'])) {
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
        $update->execute([$name, $email, $pass, $id]);
    } else {
        // Update without changing the password
        $update = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $update->execute([$name, $email, $id]);
    }
    $success_msg = "Admin updated successfully!";
    
    // Refresh displayed data
    $admin['name'] = $name;
    $admin['email'] = $email;
}

require_once "sidebar.php";
?>

<main class="add-product-page">
    <div class="page-header">
        <h2><i class="fas fa-user-edit"></i> Edit Admin: <?php echo htmlspecialchars($admin['name']); ?></h2>
        <a href="admins.php" class="back-btn" style="padding:10px 20px ;border-radius: 10px;color:#fff"><i
                class="fas fa-arrow-left"></i> Cancel</a>
    </div>

    <div class="form-container">
        <?php if ($success_msg): ?>
        <div
            style="background: #d1e7dd; color: #0f5132; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 5px solid #198754;">
            <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-row">
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($admin['name']); ?>"
                        required>
                </div>
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label>New Password (Leave blank to keep current)</label>
                    <input type="password" name="password" placeholder="••••••••">
                </div>
            </div>

            <button type="submit" name="update_admin" class="submit-btn" style="background: var(--color-primary);">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>
</main>