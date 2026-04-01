<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php'; 

/**
 * Core function to handle sending emails via SMTP
 */
function sendMail($userEmail, $userName, $subject, $messageContent) {
    $mail = new PHPMailer(true);

    try {
        // --- 1. Server Settings ---
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'ahmed.hossam.23098@gmail.com';         // SMTP username
        $mail->Password   = 'fobtekpjautxucxu';                     // App Password (Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
        $mail->Port       = 587;                                    // TCP port to connect to

        // --- 2. SSL/TLS Certificate Bypass ---
        // This resolves issues with local servers (like XAMPP) failing to verify SSL certificates
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // --- 3. Recipients ---
        $mail->setFrom('ahmed.hossam.23098@gmail.com', 'E-Shopper Team');
        $mail->addAddress($userEmail, $userName);                   // Add the user as the recipient
        $mail->addReplyTo('ahmed.hossam.23098@gmail.com', 'Support');

        // --- 4. Content ---
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = $subject;
        $mail->XMailer = 'Microsoft Outlook';                       // Masking the mailer to avoid Spam filters
        
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px;'>
                <h2 style='color: #D19C97;'>E-Shopper Support</h2>
                $messageContent
                <hr style='border:none; border-top:1px solid #eee;'>
                <p style='font-size:12px; color:#777;'>If you didn't request this, please ignore this email.</p>
            </div>";

        // Set to 2 or 3 for debugging connection issues, 0 for production
        $mail->SMTPDebug = 0; 

        // --- 5. Execution ---
        $mail->send();
        return true;

    } catch (Exception $e) {
        // Stop execution and display the error if sending fails
        die("Mailer Error: " . $mail->ErrorInfo);
    }
}

/**
 * Sends a registration verification code
 */
function sendVerificationEmail($email, $name, $code) {
    $msg = "<p>Welcome <b>$name</b>! Your verification code is:</p>
            <h1 style='letter-spacing:5px; background:#f9f9f9; padding:10px; text-align:center;'>$code</h1>";
    return sendMail($email, $name, "Verify Your Account", $msg);
}

/**
 * Sends a password reset code
 */
function sendResetEmail($email, $name, $code) {
    $msg = "<p>Hello <b>$name</b>, use the following code to reset your password:</p>
            <h1 style='letter-spacing:5px; color: #e74c3c; background:#f9f9f9; padding:10px; text-align:center;'>$code</h1>";
    return sendMail($email, $name, "Password Reset Code", $msg);
}