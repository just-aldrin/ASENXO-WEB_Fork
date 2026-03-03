<?php
error_log("send_otp.php called with: " . file_get_contents('php://input'));

/* require 'vendor/autoload.php';
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// 1. Read input
$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$otp   = trim($data['otp'] ?? '');

if (empty($email) || empty($otp)) {
    echo json_encode(['success' => false, 'error' => 'Missing email or OTP']);
    exit;
}

// 2. Store OTP in Supabase (using direct PostgreSQL connection)
$db_host = 'db.yourproject.supabase.co';  // from Supabase dashboard
$db_name = 'postgres';
$db_user = 'postgres';
$db_pass = 'your-db-password';

try {
    $pdo = new PDO("pgsql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Insert or replace OTP with 10-minute expiry
    $stmt = $pdo->prepare("
        INSERT INTO email_verifications (email, otp, expires_at)
        VALUES (:email, :otp, NOW() + INTERVAL '10 minutes')
        ON CONFLICT (email) DO UPDATE
        SET otp = EXCLUDED.otp, expires_at = EXCLUDED.expires_at
    ");
    $stmt->execute(['email' => $email, 'otp' => $otp]);
} catch (PDOException $e) {
    error_log("DB error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error']);
    exit;
}

// 3. Send email via PHPMailer (same as before)
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dost.asenxo@gmail.com';
    $mail->Password   = 'qkoczbdhdfcmqnoi';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('dost.asenxo@gmail.com', 'ASENXO');
    $mail->addAddress($email);
    $mail->isHTML(false);
    $mail->Subject = 'Your OTP Code';
    $mail->Body    = "Your OTP code is: $otp";

    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error'   => $mail->ErrorInfo
    ]);

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}