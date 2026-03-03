<?php
header('Content-Type: application/json');

// Disable raw error output to prevent the "Unexpected token <" error
error_reporting(0); 
ini_set('display_errors', 0);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 1. Connection Details - Host must NOT include 'https://'
$db_host = 'db.hmxrblblcpbikkxcwwni.supabase.co'; 
$db_name = 'ASENXO=Project';
$db_user = 'postgres';
$db_pass = 'qkoczbdhdfcmqnoi'; 
$db_port = 6543;

// 2. Get Input Data
$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$otp   = trim($data['otp'] ?? '');

if (empty($email) || empty($otp)) {
    echo json_encode(['success' => false, 'error' => 'Missing email or OTP']);
    exit;
}

try {
    // 3. Database Connection (PostgreSQL)
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 4. Save/Update OTP in Database
    $stmt = $pdo->prepare("
        INSERT INTO email_verifications (email, otp, expires_at)
        VALUES (:email, :otp, NOW() + INTERVAL '10 minutes')
        ON CONFLICT (email) DO UPDATE
        SET otp = EXCLUDED.otp, expires_at = EXCLUDED.expires_at
    ");
    $stmt->execute(['email' => $email, 'otp' => $otp]);

    // 5. Send Email via PHPMailer
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
    $mail->Subject = 'Your ASENXO Verification Code';
    $mail->Body    = "<h3>Verification Code</h3><p>Your OTP code is: <b>$otp</b></p>";

    $mail->send();
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Mailer error: ' . $mail->ErrorInfo]);
}
?>