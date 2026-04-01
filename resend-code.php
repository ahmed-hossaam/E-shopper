<?php
session_start();
require_once "./includes/db.php";
require_once "send-email.php";

// 1. Check if there is an email in session to send to
if (!isset($_SESSION['user_email_to_verify'])) {
    header("Location: signup.php");
    exit;
}

$email = $_SESSION['user_email_to_verify'];

// 2. Fetch user data from database
$stmt = $conn->prepare("SELECT name, is_verified FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    // Redirect if already verified
    if ($user['is_verified'] == 1) {
        $_SESSION['auth_msg'] = "Your account is already verified. Please login.";
        header("Location: login.php");
        exit;
    }

    // 3. Generate new OTP and update database
    $new_otp = rand(100000, 999999);
    $update = $conn->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
    
    if ($update->execute([$new_otp, $email])) {
        // 4. Send the verification email
        if (sendVerificationEmail($email, $user['name'], $new_otp)) {
            $_SESSION['auth_msg'] = "A new verification code has been sent to your email.";
        } else {
            $_SESSION['auth_msg'] = "Failed to send email. Please try again later.";
        }
        header("Location: verify.php");
        exit;
    }
} else {
    header("Location: signup.php");
    exit;
}