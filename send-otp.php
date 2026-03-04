<?php
// send-otp.php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database configuration
$db_host = 'db.hmxrblblcpbikkxcwwni.supabase.co';
$db_name = 'postgres';
$db_user = 'postgres';
$db_pass = 'qkoczbdhdfcmqnoi';
$db_port = 6543;

// Get input data
$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$otp = trim($data['otp'] ?? '');
$firstName = trim($data['firstName'] ?? 'User');
$lastName = trim($data['lastName'] ?? '');

if (empty($email) || empty($otp)) {
    echo json_encode(['success' => false, 'error' => 'Email and OTP are required']);
    exit;
}

try {
    // Connect to database
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Save OTP in database
    $stmt = $pdo->prepare("
        INSERT INTO email_verifications (email, otp, expires_at, attempts)
        VALUES (:email, :otp, NOW() + INTERVAL '10 minutes', 0)
        ON CONFLICT (email) DO UPDATE
        SET otp = EXCLUDED.otp, 
            expires_at = EXCLUDED.expires_at,
            attempts = 0,
            updated_at = NOW()
    ");
    $stmt->execute(['email' => $email, 'otp' => $otp]);

    // Send email via PHPMailer
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
    $mail->Subject = 'Verify Your ASENXO Account';
    
    // Build verification link
    $verificationLink = 'https://' . $_SERVER['HTTP_HOST'] . '/verification.php?email=' . urlencode($email);
    
    // HTML email template
    $mail->Body = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #e2b974; color: #000; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { padding: 30px; background: #f9f9f9; border-radius: 0 0 10px 10px; }
            .otp-code { 
                font-size: 36px; 
                font-weight: bold; 
                color: #e2b974; 
                text-align: center; 
                padding: 20px;
                background: #fff;
                border-radius: 10px;
                margin: 20px 0;
                letter-spacing: 5px;
                border: 2px dashed #e2b974;
            }
            .button {
                display: inline-block;
                padding: 15px 30px;
                background: #e2b974;
                color: #000;
                text-decoration: none;
                border-radius: 8px;
                font-weight: bold;
                margin: 20px 0;
            }
            .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            .note { background: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 10px; border-radius: 5px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Welcome to ASENXO, " . htmlspecialchars($firstName) . "!</h2>
            </div>
            <div class='content'>
                <p>Thank you for registering with ASENXO. To complete your registration, please verify your email address using one of the methods below.</p>
                
                <div style='text-align: center;'>
                    <div class='otp-code'>" . $otp . "</div>
                    
                    <p><strong>⏰ This code expires in 10 minutes</strong></p>
                    
                    <a href='" . $verificationLink . "' class='button'>Continue Here →</a>
                </div>
                
                <div class='note'>
                    <strong>📱 Can't click the button?</strong><br>
                    Copy and paste this link into your browser:<br>
                    <a href='" . $verificationLink . "' style='color: #e2b974; word-break: break-all;'>" . $verificationLink . "</a>
                </div>
                
                <p><strong>Or enter the verification code manually:</strong></p>
                <ol style='color: #666;'>
                    <li>Go to the verification page</li>
                    <li>Enter the 6-digit code: <strong>" . $otp . "</strong></li>
                    <li>Click Verify Email</li>
                </ol>
                
                <p>If you didn't create an account with ASENXO, please ignore this email.</p>
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " ASENXO. All rights reserved.</p>
                <p>This is an automated message, please do not reply.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    $mail->send();
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Mailer error: ' . $mail->ErrorInfo]);
}
?>