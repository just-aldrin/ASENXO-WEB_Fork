<?php
// verify-otp.php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Supabase Database Connection - UPDATE THESE VALUES
$db_host = 'aws-1-ap-southeast-2.pooler.supabase.com';
$db_port = 6543;
$db_name = 'postgres';
$db_user = 'postgres.hmxrblblcpbikkxcwwni';
$db_pass = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw';
// old pass > qkoczbdhdfcmqnoi

// Get input data
$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$otp = trim($data['otp'] ?? '');
$firstName = trim($data['first_name'] ?? '');
$lastName = trim($data['last_name'] ?? '');
$referralCode = trim($data['referral_code'] ?? '');

if (empty($email) || empty($otp)) {
    echo json_encode(['success' => false, 'error' => 'Email and OTP are required']);
    exit;
}

try {
    // Connect to Supabase PostgreSQL
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;sslmode=require";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
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
    $verification = $stmt->fetch();

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

    // Get user from auth.users and insert/update user_profiles
    // Note: You'll need to get the user ID from Supabase Auth
    // For now, we'll create/update the profile
    $stmt = $pdo->prepare("
        INSERT INTO user_profiles (id, email, first_name, last_name, referral_code, email_verified)
        VALUES (
            gen_random_uuid(), 
            :email, 
            :first_name, 
            :last_name, 
            :referral_code, 
            TRUE
        )
        ON CONFLICT (email) DO UPDATE
        SET email_verified = TRUE, 
            first_name = EXCLUDED.first_name,
            last_name = EXCLUDED.last_name,
            referral_code = EXCLUDED.referral_code,
            updated_at = NOW()
    ");
    
    $stmt->execute([
        'email' => $email,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'referral_code' => $referralCode
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

        $mail->setFrom('dost.asenxo@gmail.com', 'ASENXO');
        $mail->addAddress($email, "$firstName $lastName");
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to ASENXO!';
        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: 'Inter', sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #e2b974; color: #000; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { padding: 30px; background: #f9f9f9; }
                .button { display: inline-block; background: #e2b974; color: #000; padding: 12px 30px; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Welcome to ASENXO!</h1>
                </div>
                <div class='content'>
                    <h2>Hi " . htmlspecialchars($firstName) . ",</h2>
                    <p>Your email has been successfully verified. You can now log in to your account and start using ASENXO.</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='http://" . $_SERVER['HTTP_HOST'] . "/login-mock.php' class='button'>Log In to Your Account</a>
                    </div>
                    <p>If you have any questions, feel free to contact our support team.</p>
                    <p>Best regards,<br>The ASENXO Team</p>
                </div>
            </div>
        </body>
        </html>
        ";
        $mail->send();
    } catch (Exception $e) {
        // Log welcome email error but don't fail verification
        error_log("Welcome email failed: " . $e->getMessage());
    }

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    if (isset($pdo)) $pdo->rollBack();
    error_log("Database error in verify-otp.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    if (isset($pdo)) $pdo->rollBack();
    error_log("Server error in verify-otp.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>