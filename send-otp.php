<?php
// send-otp.php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Supabase Database Connection - UPDATE THESE VALUES FROM YOUR DASHBOARD
$db_host = 'aws-1-ap-southeast-2.pooler.supabase.com'; // From connection string
$db_port = 6543;
$db_name = 'postgres';
$db_user = 'postgres.hmxrblblcpbikkxcwwni'; // Your project reference
$db_pass = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; // Your database password
// old pass > qkoczbdhdfcmqnoi

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
    // Connect to Supabase PostgreSQL
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;sslmode=require";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 5
    ]);

    // Store OTP in database
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
    
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Set to DEBUG_SERVER for debugging
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
    $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $verificationLink = $protocol . $_SERVER['HTTP_HOST'] . '/verification.php?email=' . urlencode($email);
    
    // HTML email template
    $mail->Body = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style>
            body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f4f4f4; }
            .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
            .header { background: #e2b974; padding: 30px 20px; text-align: center; }
            .header h1 { margin: 0; color: #000; font-size: 28px; font-weight: 700; }
            .content { padding: 40px 30px; background: #ffffff; }
            .content p { color: #333; font-size: 16px; margin-bottom: 20px; }
            .otp-container { background: #f8f8f8; border-radius: 12px; padding: 30px; text-align: center; margin: 30px 0; border: 2px dashed #e2b974; }
            .otp-code { font-size: 48px; font-weight: 800; letter-spacing: 8px; color: #e2b974; margin: 20px 0; font-family: monospace; }
            .button { display: inline-block; background: #e2b974; color: #000; text-decoration: none; padding: 15px 40px; border-radius: 8px; font-weight: 600; font-size: 16px; margin: 20px 0; }
            .button:hover { background: #d4a95c; }
            .footer { background: #f8f8f8; padding: 20px; text-align: center; color: #666; font-size: 14px; border-top: 1px solid #eee; }
            .note { background: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 15px; border-radius: 8px; margin: 20px 0; font-size: 14px; }
            hr { border: none; border-top: 1px solid #eee; margin: 30px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>ASENXO</h1>
            </div>
            <div class='content'>
                <h2 style='margin-top: 0; color: #333;'>Welcome to ASENXO, " . htmlspecialchars($firstName) . "!</h2>
                
                <p>Thank you for registering. To complete your registration and start using ASENXO, please verify your email address.</p>
                
                <div class='otp-container'>
                    <p style='margin: 0; color: #666; font-size: 14px;'>Your verification code is:</p>
                    <div class='otp-code'>" . $otp . "</div>
                    <p style='margin: 10px 0 0; color: #999; font-size: 14px;'>Valid for 10 minutes</p>
                </div>
                
                <div style='text-align: center;'>
                    <a href='" . $verificationLink . "' class='button'>Verify Email Address</a>
                </div>
                
                <div class='note'>
                    <strong>⏰ This code expires in 10 minutes</strong><br>
                    If you didn't request this verification, please ignore this email.
                </div>
                
                <hr>
                
                <p style='color: #666; font-size: 14px;'><strong>Can't click the button?</strong> Copy and paste this link into your browser:</p>
                <p style='background: #f5f5f5; padding: 10px; border-radius: 5px; word-break: break-all; font-size: 12px; color: #e2b974;'>" . $verificationLink . "</p>
                
                <p style='color: #666; font-size: 14px;'>Or enter the code manually: <strong style='color: #e2b974;'>" . $otp . "</strong></p>
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " ASENXO. All rights reserved.</p>
                <p style='font-size: 12px; color: #999;'>This is an automated message, please do not reply.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Plain text alternative
    $mail->AltBody = "Welcome to ASENXO, $firstName!\n\nYour verification code is: $otp\n\nClick here to verify: $verificationLink\n\nThis code expires in 10 minutes.";

    $mail->send();
    
    echo json_encode(['success' => true, 'message' => 'OTP sent successfully']);

} catch (PDOException $e) {
    error_log("Database error in send-otp.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    error_log("Mailer error in send-otp.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Mailer error: ' . $e->getMessage()]);
}
?>