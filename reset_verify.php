<?php
session_start();
require_once "./includes/db.php";

if (!isset($_SESSION['reset_email'])) {
    header("Location: reset_email.php");
    exit();
}

if (isset($_POST["verify_code"])) {
    $email = $_SESSION['reset_email'];
    $code = $_POST['c1'].$_POST['c2'].$_POST['c3'].$_POST['c4'].$_POST['c5'].$_POST['c6'];
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND verification_code = ? LIMIT 1");
    $stmt->execute([$email, $code]);
    
    if ($stmt->fetch()) {
        $_SESSION['code_verified'] = true;
        header("Location: reset-password.php");
        exit();
    } else {
        $error = "Invalid verification code!";
    }
}

require_once "./includes/header.php";
?>

<style>
.otp-input {
    width: 45px;
    height: 55px;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin: 0 5px;
    border: 2px solid #ddd;
    border-radius: 5px;
}

.otp-input:focus {
    border-color: #D19C97;
    outline: none;
    box-shadow: 0 0 5px rgba(209, 156, 151, 0.5);
}
</style>

<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Verify Code</span></h2>
    </div>
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-5 col-md-8 text-center">
            <div class="bg-light p-30">
                <i class="fa fa-shield-alt fa-3x text-primary mb-3"></i>
                <p>We've sent a 6-digit code to <br><strong><?= htmlspecialchars($_SESSION['reset_email']) ?></strong>
                </p>

                <?php if(isset($error)): ?>
                <div class="alert alert-danger py-2"><?= $error ?></div>
                <?php endif; ?>

                <?php if(isset($_SESSION['auth_msg'])): ?>
                <div class="alert alert-success py-2"><?= $_SESSION['auth_msg']; unset($_SESSION['auth_msg']); ?></div>
                <?php endif; ?>

                <form method="POST" class="mt-4">
                    <div class="d-flex justify-content-center mb-4">
                        <input type="text" name="c1" class="otp-input" maxlength="1" required
                            oninput="if(this.value.length==1) this.nextElementSibling?.focus()">
                        <input type="text" name="c2" class="otp-input" maxlength="1" required
                            oninput="if(this.value.length==1) this.nextElementSibling?.focus()">
                        <input type="text" name="c3" class="otp-input" maxlength="1" required
                            oninput="if(this.value.length==1) this.nextElementSibling?.focus()">
                        <input type="text" name="c4" class="otp-input" maxlength="1" required
                            oninput="if(this.value.length==1) this.nextElementSibling?.focus()">
                        <input type="text" name="c5" class="otp-input" maxlength="1" required
                            oninput="if(this.value.length==1) this.nextElementSibling?.focus()">
                        <input type="text" name="c6" class="otp-input" maxlength="1" required>
                    </div>

                    <button type="submit" name="verify_code"
                        class="btn btn-primary btn-block py-3 font-weight-bold">Verify & Continue</button>
                </form>

                <p class="mt-4 mb-0 text-muted">Didn't receive code?
                    <a href="resend_reset_code.php" class="text-primary font-weight-bold">Resend Code</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once "./includes/footer.php"; ?>