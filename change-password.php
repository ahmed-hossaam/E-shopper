<?php
session_start();
require_once "./includes/db.php";

// Check authentication
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php"); 
//     exit;
// }

if (isset($_POST['update_pass'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $c_pass   = $_POST['confirm_password'];
    $user_id  = $_SESSION['user_id'];

    // 1. Fetch the stored password from database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = "User not found!";
    } elseif (!password_verify($old_pass, $user['password'])) {
        $error = "Old password is incorrect!";
    } elseif ($new_pass !== $c_pass) {
        $error = "New passwords do not match!";
    } elseif (strlen($new_pass) < 8) {
        $error = "Password must be at least 8 characters!";
    } else {
        // Update to new hashed password
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($update->execute([$hashed, $user_id])) {
            $success = "Password changed successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

include "./includes/header.php";
?>

<div class="container-fluid pt-5">
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="bg-light p-4 shadow-sm border rounded">
                <h4 class="font-weight-semi-bold mb-4 text-center">Change Password</h4>

                <?php if(isset($error)): ?> <div class="alert alert-danger"><?= $error ?></div> <?php endif; ?>
                <?php if(isset($success)): ?> <div class="alert alert-success"><?= $success ?></div> <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="old_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" name="update_pass"
                        class="btn btn-primary btn-block py-3 font-weight-bold">Update Password</button>
                    <a href="profile.php" class="btn btn-link btn-block text-muted">Back to Profile</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "./includes/footer.php"; ?>