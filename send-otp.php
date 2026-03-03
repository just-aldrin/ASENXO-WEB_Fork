<?php
header('Content-Type: application/json');

error_reporting(0); 
ini_set('display_errors', 0);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$db_host = 'https://hmxrblblcpbikkxcwwni.supabase.co'; // Get this from Supabase Settings > Database
$db_name = 'postgres';
$db_user = 'postgres';
$db_pass = 'qkoczbdhdfcmqnoi';

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$otp   = trim($data['otp'] ?? '');

if (empty($email) || empty($otp)) {
    echo json_encode(['success' => false, 'error' => 'Missing email or OTP']);
    exit;
}


// 4. Attempt Connection
$conn = mysqli_connect($host, $user, $pass, $db, $port);

// --- INSERT THIS PART HERE ---
if (!$conn) {
    echo json_encode([
        "success" => false, 
        "error" => "Database connection failed: " . mysqli_connect_error()
    ]);
    exit; // Stop the script here so it doesn't try to send an email
}
// -----------------------------

try {
    $pdo = new PDO("pgsql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("
        INSERT INTO email_verifications (email, otp, expires_at)
        VALUES (:email, :otp, NOW() + INTERVAL '10 minutes')
        ON CONFLICT (email) DO UPDATE
        SET otp = EXCLUDED.otp, expires_at = EXCLUDED.expires_at
    ");
    $stmt->execute(['email' => $email, 'otp' => $otp]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// 3. Send Email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dost.asenxo@gmail.com';
    $mail->Password   = 'qkoczbdhdfcmqnoi'; // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('dost.asenxo@gmail.com', 'ASENXO');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Your ASENXO Verification Code';
    $mail->Body    = "<h3>Verification Code</h3><p>Your OTP code is: <b>$otp</b></p>";

    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
}
