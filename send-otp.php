<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$otp = trim($data['otp'] ?? '');
$firstName = trim($data['firstName'] ?? 'User');

if (empty($email) || empty($otp)) {
    echo json_encode(['success' => false, 'error' => 'Missing email or OTP data.']);
    exit;
}

try {
    $mail = new PHPMailer(true);
    
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dost.asenxo@gmail.com';
    $mail->Password   = 'qkoczbdhdfcmqnoi'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('dost.asenxo@gmail.com', 'ASENXO');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Your ASENXO Verification Code: $otp";
    
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $verificationLink = $protocol . $host . '/THS/ASENXO-WEB/verification.php?email=' . urlencode($email);
    
    $mail->Body = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden;'>
        <div style='background: #00df8d; color: #000; padding: 20px; text-align: center;'>
            <h2>Welcome to ASENXO, " . htmlspecialchars($firstName) . "!</h2>
        </div>
        <div style='padding: 30px; text-align: center;'>
            <p style='font-size: 16px; color: #555;'>Your 6-digit verification code is:</p>
            <h1 style='font-size: 48px; letter-spacing: 10px; color: #1e293b; margin: 20px 0;'>$otp</h1>
            <p style='color: #888;'>This code will expire in 10 minutes.</p>
            <div style='margin-top: 30px;'>
                <a href='$verificationLink' style='background: #00df8d; color: #000; padding: 15px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verify Email Now</a>
            </div>
        </div>
    </div>";

    $mail->AltBody = "Welcome to ASENXO! Your verification code is: $otp. Link: $verificationLink";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Email sent successfully']);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'error' => 'Mailer Error: ' . $mail->ErrorInfo
    ]);
}