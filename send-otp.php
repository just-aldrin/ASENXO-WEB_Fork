<?php
// send-otp.php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get input data
$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$otp = trim($data['otp'] ?? '');
$firstName = trim($data['firstName'] ?? 'User');
$lastName = trim($data['lastName'] ?? '');

if (empty($email) || empty($otp)) {
    echo json_encode(['success' => false, 'error' => 'Missing email or OTP']);
    exit;
}

try {
    // Send email via PHPMailer
    $mail = new PHPMailer(true);
    
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dost.asenxo@gmail.com';
    $mail->Password   = 'qkoczbdhdfcmqnoi'; // Use App Password if 2FA is enabled
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->Timeout    = 30;

    // Recipients
    $mail->setFrom('dost.asenxo@gmail.com', 'ASENXO');
    $mail->addAddress($email, "$firstName $lastName");
    $mail->addReplyTo('support@asenxo.com', 'ASENXO Support');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Verify Your ASENXO Account';
    
    // Build verification link
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $verificationLink = $protocol . $_SERVER['HTTP_HOST'] . '/THS/ASENXO-WEB/verification.php?email=' . urlencode($email);
    
    // Simple HTML email template
    $mail->Body = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #e2b974; color: #000; padding: 20px; text-align: center; }
            .otp-code { font-size: 32px; font-weight: bold; color: #e2b974; text-align: center; padding: 20px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Welcome to ASENXO, " . htmlspecialchars($firstName) . "!</h2>
            </div>
            <p>Thank you for registering. Your verification code is:</p>
            <div class='otp-code'>" . $otp . "</div>
            <p>This code expires in 10 minutes.</p>
            <p>Or click this link to verify: <a href='" . $verificationLink . "'>Verify Email</a></p>
        </div>
    </body>
    </html>
    ";
    
    // Plain text alternative
    $mail->AltBody = "Welcome to ASENXO, $firstName!\n\nYour verification code is: $otp\n\nClick here to verify: $verificationLink\n\nThis code expires in 10 minutes.";

    $mail->send();
    
    // Return success even if email fails (for testing)
    echo json_encode(['success' => true, 'message' => 'OTP sent successfully']);

} catch (Exception $e) {
    // Log the error but still return success for testing
    error_log("Mailer error in send-otp.php: " . $e->getMessage());
    
    // For testing purposes, return success anyway so the flow continues
    echo json_encode(['success' => true, 'warning' => 'Email sending failed, but continuing with session storage']);
}
?>