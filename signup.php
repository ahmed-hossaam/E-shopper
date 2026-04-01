<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once "./includes/db.php";

// Redirect if user is already logged in and verified
if (isset($_SESSION['user_id']) && !isset($_SESSION['user_email_to_verify'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['signup'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $c_pass   = $_POST['c_password'];

    // Input Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif ($password !== $c_pass) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters!";
    } else {
        // Check if Email already exists
        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkEmail->execute([$email]);

        if ($checkEmail->rowCount() > 0) {
            $error = "This email is already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $v_code = rand(100000, 999999);

            // Insert new user as unverified
            $insert = $conn->prepare("INSERT INTO users (name, email, password, verification_code) VALUES (?, ?, ?, ?)");

            if ($insert->execute([$name, $email, $hashed_password, $v_code])) {
                
                // Store email in session for the verification step
                $_SESSION['user_email_to_verify'] = $email;

                require_once "send-email.php";
                
                if (sendVerificationEmail($email, $name, $v_code)) {
                    $_SESSION['auth_msg'] = "Registration success! Please check your email for the code.";
                } else {
                    $_SESSION['auth_msg'] = "Account created, but email delivery failed. You can resend the code.";
                }

                header("Location: verify.php");
                exit;
            } else {
                $error = "Something went wrong. Please try again later.";
            }
        }
    }
}

include "./includes/header.php";
?>

<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Create New Account</span></h2>
    </div>
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="bg-light p-30">

                <?php if (isset($error)): ?>
                <div class="alert alert-danger border-0 shadow-sm text-center">
                    <i class="fa fa-exclamation-triangle mr-2"></i> <?= $error ?>
                </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-11 form-group">
                            <label class="font-weight-semi-bold">Full Name</label>
                            <input class="form-control py-4" type="text" name="name"
                                value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" placeholder="John Doe"
                                required>
                        </div>

                        <div class="col-md-11 form-group">
                            <label class="font-weight-semi-bold">Email Address</label>
                            <input class="form-control py-4" type="email" name="email"
                                value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
                                placeholder="example@email.com" required>
                        </div>

                        <div class="col-md-11 form-group">
                            <label class="font-weight-semi-bold">Password</label>
                            <input class="form-control py-4" type="password" name="password" placeholder="••••••••"
                                required>
                        </div>

                        <div class="col-md-11 form-group">
                            <label class="font-weight-semi-bold">Confirm Password</label>
                            <input class="form-control py-4" type="password" name="c_password" placeholder="••••••••"
                                required>
                        </div>

                        <div class="col-md-11 mt-3">
                            <button type="submit" name="signup"
                                class="btn btn-primary btn-block py-3 font-weight-bold shadow">
                                REGISTER & VERIFY
                            </button>
                        </div>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0 text-muted">Already have an account?
                        <a href="login.php" class="text-primary font-weight-bold">Login Here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "./includes/footer.php"; ?>