<?php
session_start();
require_once "./includes/db.php";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Error: Please fill in all fields!";
    } else {
        // 1. Fetch user by email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // 2. Verify password hash
            if (password_verify($password, $user['password'])) {
                
                // Set unified session variables
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name']; 
                $_SESSION['user_email_to_verify'] = $user['email'];

                // Check verification status
                if ($user['is_verified'] == 0) {
                    $_SESSION['auth_msg'] = "Please verify your account, " . htmlspecialchars($user['name']);
                    header("Location: verify.php");
                    exit;        
                } else {
                    header("Location: index.php");
                    exit;
                }
            } else {
                $error = "Error: Incorrect password. Please try again!";
            }
        } else {
            $error = "Error: This email is not registered. <a href='signup.php' class='text-primary'>Create an account?</a>";
        }
    }
}

include "./includes/header.php";
?>

<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Login</span></h2>
    </div>
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="bg-light p-4 ">

                <?php if(isset($_SESSION['auth_msg'])): ?>
                <div class="alert alert-success border-0 shadow-sm">
                    <?= $_SESSION['auth_msg']; unset($_SESSION['auth_msg']); ?>
                </div>
                <?php endif; ?>

                <?php if(isset($error)): ?>
                <div class="alert alert-danger border-0 shadow-sm">
                    <i class="fa fa-exclamation-triangle mr-2"></i> <?= $error ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="font-weight-semi-bold">Email Address</label>
                        <input class="form-control py-4" type="email" name="email"
                            value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" placeholder="example@email.com"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-semi-bold">Password</label>
                        <input class="form-control py-4" type="password" name="password" placeholder="••••••••"
                            required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="remember">
                            <label class="custom-control-label" for="remember">Remember me</label>
                        </div>
                        <a href="reset_email.php" class="text-primary small font-weight-bold">Forgot Password?</a>
                    </div>

                    <button type="submit" name="login" class="btn btn-primary btn-block py-3 font-weight-bold shadow">
                        LOGIN
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0 text-muted">Don't have an account?
                        <a href="signup.php" class="text-primary font-weight-bold">Register Now</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "./includes/footer.php"; ?>