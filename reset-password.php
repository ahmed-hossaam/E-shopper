<?php
session_start();
require_once "./includes/db.php";

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['code_verified'])) {
    header("Location: reset_email.php");
    exit;
}

if (isset($_POST['reset_password_btn'])) {
    $new_pass = $_POST['new_password'];
    $conf_pass = $_POST['conf_password'];
    $email = $_SESSION['reset_email'];

    if ($new_pass !== $conf_pass) {
        $error = "Passwords do not match!";
    } elseif (strlen($new_pass) < 8) {
        $error = "Password must be at least 8 characters!";
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ?, verification_code = NULL WHERE email = ?");
        
        if ($update->execute([$hashed_pass, $email])) {
            unset($_SESSION['reset_email']);
            unset($_SESSION['code_verified']);
            $_SESSION['auth_msg'] = "Password updated! You can login now.";
            header("Location: login.php");
            exit;
        }
    }
}

include "./includes/header.php";
?>

<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Create New Password</span></h2>
    </div>
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="bg-light p-30">
                <p class="text-center mb-4">Please enter your new secure password below.</p>

                <?php if(isset($error)): ?>
                <div class="alert alert-danger py-2 text-center"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label class="font-weight-semi-bold">New Password</label>
                        <input type="password" name="new_password" class="form-control py-4"
                            placeholder="Min. 8 characters" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-semi-bold">Confirm New Password</label>
                        <input type="password" name="conf_password" class="form-control py-4"
                            placeholder="Repeat new password" required>
                    </div>
                    <div class="mt-4">
                        <button type="submit" name="reset_password_btn"
                            class="btn btn-primary btn-block py-3 font-weight-bold shadow">UPDATE PASSWORD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "./includes/footer.php"; ?>