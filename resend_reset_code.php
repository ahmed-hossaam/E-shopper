<?php
session_start();
require_once "./includes/db.php";
require_once "send-email.php";

if (!isset($_SESSION['reset_email'])) {
    header("Location: reset_email.php");
    exit;
}

$email = $_SESSION['reset_email'];

$stmt = $conn->prepare("SELECT name FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    $new_otp = rand(100000, 999999);
    $update = $conn->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
    
    if ($update->execute([$new_otp, $email])) {
        if (sendResetEmail($email, $user['name'], $new_otp)) {
            $_SESSION['auth_msg'] = "A new reset code has been sent to your email.";
        } else {
            $_SESSION['auth_msg'] = "Error sending email. Try again.";
        }
    }
}
header("Location: reset_verify.php");
exit;