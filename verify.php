<?php
session_start();
require_once "./includes/db.php";

// Protection: If the page is accessed without an email in the session, redirect to signup
if (!isset($_SESSION['user_email_to_verify'])) {
    header("Location: signup.php");
    exit;
}


if (isset($_POST['verify_code_btn'])) {
    // Concatenate the code from the 6 input fields
    $user_code = $_POST['c1'].$_POST['c2'].$_POST['c3'].$_POST['c4'].$_POST['c5'].$_POST['c6'];
    $email = $_SESSION['user_email_to_verify'];
    
    // Validation
    if (strlen($user_code) !== 6 || !ctype_digit($user_code)) {
        $error = "Error: Please enter the full 6-digit code!";
        } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND verification_code = ? LIMIT 1");
        $stmt->execute([$email, $user_code]);
        $user = $stmt->fetch();

        if ($user) {
            $update = $conn->prepare("UPDATE users SET is_verified = 1, verification_code = NULL WHERE email = ?");
            $update->execute([$email]);

            // Clear session after successful verification
            unset($_SESSION['user_email_to_verify']);

            $_SESSION['auth_msg'] = "Success! Account verified. You can login now.";
            header("Location: index.php");
            exit;
        } else {
            $error = "Error: Invalid verification code!";
        }
    }
}
require_once "./includes/header.php";
?>

<style>
/* Style for the 6 OTP input fields */
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
        <h2 class="section-title px-5"><span class="px-2">Verify Account</span></h2>
    </div>

    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="bg-light p-30 text-center">
                <i class="fa fa-user-shield fa-3x text-primary mb-3"></i>
                <p class="mb-4">We've sent a code to
                    <br><strong><?= htmlspecialchars($_SESSION['user_email_to_verify']) ?></strong>
                </p>

                <?php if (isset($error)): ?>
                <div class="alert alert-danger py-2 text-center"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" id="otp-form">
                    <div class="d-flex justify-content-center mb-4">
                        <input type="text" name="c1" class="otp-input" maxlength="1" inputmode="numeric" required>
                        <input type="text" name="c2" class="otp-input" maxlength="1" inputmode="numeric" required>
                        <input type="text" name="c3" class="otp-input" maxlength="1" inputmode="numeric" required>
                        <input type="text" name="c4" class="otp-input" maxlength="1" inputmode="numeric" required>
                        <input type="text" name="c5" class="otp-input" maxlength="1" inputmode="numeric" required>
                        <input type="text" name="c6" class="otp-input" maxlength="1" inputmode="numeric" required>
                    </div>

                    <button type="submit" name="verify_code_btn"
                        class="btn btn-primary btn-block py-3 font-weight-bold shadow">
                        VERIFY NOW
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="small mb-0 text-muted">Didn't receive the code?</p>
                    <a href="resend-code.php" class="text-primary font-weight-bold">Resend New Code</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const inputs = document.querySelectorAll('.otp-input');
inputs.forEach((input, index) => {
    input.addEventListener('input', (e) => {
        if (e.inputType === "deleteContentBackward") return;
        input.value = input.value.replace(/[^0-9]/g, '');
        if (input.value && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && index > 0) {
            inputs[index - 1].focus();
        }
    });
});
</script>

<?php include "./includes/footer.php"; ?>