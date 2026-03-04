<?php
// verify-otp.php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database configuration - UPDATED HOST
$db_host = 'db.hmxrbbllcbpkkksxwwni.supabase.co'; // Corrected host
$db_name = 'postgres';
$db_user = 'postgres';
$db_pass = 'qkoczbdhdfcmqnoi';
$db_port = 6543;

// Get input data
$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$otp = trim($data['otp'] ?? '');

if (empty($email) || empty($otp)) {
    echo json_encode(['success' => false, 'error' => 'Email and OTP are required']);
    exit;
}

try {
    // Connect to database
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ]);

    // Start transaction
    $pdo->beginTransaction();

    // Check if OTP exists and is valid
    $stmt = $pdo->prepare("
        SELECT * FROM email_verifications 
        WHERE email = :email 
        AND otp = :otp 
        AND expires_at > NOW()
        AND attempts < 5
    ");
    $stmt->execute(['email' => $email, 'otp' => $otp]);
    $verification = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$verification) {
        // Increment attempts
        $pdo->prepare("
            UPDATE email_verifications 
            SET attempts = attempts + 1 
            WHERE email = :email
        ")->execute(['email' => $email]);
        
        $pdo->commit();
        echo json_encode(['success' => false, 'error' => 'Invalid or expired verification code']);
        exit;
    }

    // Delete used OTP
    $pdo->prepare("DELETE FROM email_verifications WHERE email = :email")->execute(['email' => $email]);

    // Get user from auth.users and insert into user_profiles
    // Note: You need to connect to Supabase Auth to get the user ID
    // For now, we'll create a basic user profile
    $stmt = $pdo->prepare("
        INSERT INTO user_profiles (id, email, first_name, last_name, referral_code, email_verified)
        VALUES (
            gen_random_uuid(), 
            :email, 
            COALESCE(:first_name, ''), 
            COALESCE(:last_name, ''), 
            COALESCE(:referral_code, ''), 
            TRUE
        )
        ON CONFLICT (email) DO UPDATE
        SET email_verified = TRUE, updated_at = CURRENT_TIMESTAMP
    ");
    
    // Get user data from session or request
    $userData = json_decode(file_get_contents('php://input'), true);
    $stmt->execute([
        'email' => $email,
        'first_name' => $userData['first_name'] ?? '',
        'last_name' => $userData['last_name'] ?? '',
        'referral_code' => $userData['referral_code'] ?? ''
    ]);

    $pdo->commit();

    // Send welcome email
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'dost.asenxo@gmail.com';
        $mail->Password   = 'qkoczbdhdfcmqnoi';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->Timeout    = 10;

        $mail->setFrom('dost.asenxo@gmail.com', 'ASENXO');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to ASENXO!';
        $mail->Body = "
            <h2>Welcome to ASENXO!</h2>
            <p>Your email has been successfully verified.</p>
            <p>You can now log in to your account and start using our services.</p>
            <p><a href='https://" . $_SERVER['HTTP_HOST'] . "/login-mock.php' style='background: #e2b974; color: #000; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Log In Now</a></p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // Log welcome email error but don't fail the verification
        error_log("Welcome email failed: " . $mail->ErrorInfo);
    }

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    if (isset($pdo)) $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    if (isset($pdo)) $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>