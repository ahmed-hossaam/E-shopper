<?php
session_start();
require_once "./includes/db.php";
require_once "send-email.php"; // Using your function

if (isset($_POST["send_code"])) {
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    
    // Check if user exists
    $stmt = $conn->prepare("SELECT name FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate 6-digit code
        $reset_code = rand(100000, 999999);
        
        // Update code in DB
        $update = $conn->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
        $update->execute([$reset_code, $email]);

        // Send Email using your sendResetEmail function
        if (sendResetEmail($email, $user['name'], $reset_code)) {
            $_SESSION['reset_email'] = $email;
            header("Location: reset_verify.php");
            exit();
        } else {
            $error = "Failed to send email. Check your SMTP settings.";
        }
    } else {
        $error = "Error: This email is not registered in our system.";
    }
}

require_once "./includes/header.php";
?>

<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Forgot Password</span></h2>
    </div>
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="bg-light p-30 text-center">
                <i class="fa fa-lock fa-3x text-primary mb-3"></i>
                <p class="mb-4">Enter your email address and we will send you a code to reset your password.</p>

                <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group text-left">
                        <label>Email Address</label>
                        <input class="form-control" type="email" name="email" placeholder="example@email.com" required>
                    </div>
                    <div class="mt-4">
                        <button type="submit" name="send_code" class="btn btn-primary btn-block py-3">Send Verification
                            Code</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="login.php" class="text-muted"><i class="fa fa-angle-left mr-2"></i>Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "./includes/footer.php"; ?>