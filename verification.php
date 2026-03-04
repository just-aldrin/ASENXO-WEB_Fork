<?php
// verification.php
session_start();
$email = $_GET['email'] ?? '';
if (empty($email)) {
    header('Location: register-mock.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASENXO | Verify Email</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0a0a0a;
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }
        .verify-card {
            background: #0e0e0e;
            border: 1px solid #222;
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        .verify-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .back-link {
            color: #888;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
        }
        .back-link:hover { color: #e2b974; }
        .logo-img { height: 30px; }
        .mail-icon {
            width: 80px;
            height: 80px;
            background: rgba(226,185,116,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #e2b974;
            font-size: 40px;
        }
        h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            text-align: center;
            margin-bottom: 10px;
            color: #fff;
        }
        .description {
            text-align: center;
            color: #888;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .email-highlight {
            text-align: center;
            background: #1a1a1a;
            padding: 15px;
            border-radius: 12px;
            color: #e2b974;
            font-weight: 500;
            margin-bottom: 30px;
            word-break: break-all;
        }
        .otp-inputs {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin: 30px 0;
        }
        .otp-box {
            width: 50px;
            height: 60px;
            border: 2px solid #333;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            background: transparent;
            color: #fff;
            transition: border-color 0.3s;
        }
        .otp-box:focus {
            border-color: #e2b974;
            outline: none;
        }
        .verify-btn {
            width: 100%;
            background: #e2b974;
            color: #000;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s;
            margin-top: 20px;
        }
        .verify-btn:hover { opacity: 0.9; }
        .verify-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .resend-section {
            text-align: center;
            margin-top: 20px;
        }
        .resend-link {
            background: none;
            border: none;
            color: #e2b974;
            cursor: pointer;
            font-size: 14px;
            text-decoration: underline;
        }
        .resend-link:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            text-decoration: none;
        }
        .timer-text {
            color: #888;
            font-size: 14px;
            margin-top: 10px;
        }
        .form-message {
            margin-top: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.9rem;
            display: none;
        }
        .form-message.success {
            background-color: rgba(46,204,113,0.15);
            color: #2ecc71;
            border: 1px solid rgba(46,204,113,0.3);
            display: block;
        }
        .form-message.error {
            background-color: rgba(231,76,60,0.15);
            color: #e74c3c;
            border: 1px solid rgba(231,76,60,0.3);
            display: block;
        }
        @keyframes cardIntro {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .verify-card { animation: cardIntro 0.7s ease-out; }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="verify-header">
            <a href="register-mock.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="index.php"><img src="src/img/logo-name.png" class="logo-img" alt="ASENXO Logo"></a>
        </div>

        <div class="mail-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>

        <h2>Verify your email</h2>
        <p class="description">
            We've sent a 6-digit verification code to
        </p>
        <div class="email-highlight" id="displayEmail">
            <?php echo htmlspecialchars($email); ?>
        </div>

        <div class="otp-inputs" id="otpInputs">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
        </div>

        <div id="verificationMessage" class="form-message"></div>

        <button class="verify-btn" id="verifyBtn">Verify Email</button>

        <div class="resend-section">
            <p class="timer-text" id="timerText">Resend code in 59 seconds</p>
            <button class="resend-link" id="resendBtn" disabled>Resend Code</button>
        </div>
        
        <p style="text-align: center; color: #666; font-size: 12px; margin-top: 20px;">
            For testing: Check the alert or console for your OTP code
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script>
        (function() {
            const SUPABASE_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
            const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw';
            const supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

            // Get email from URL or session
            const urlParams = new URLSearchParams(window.location.search);
            const email = urlParams.get('email') || sessionStorage.getItem('pending_email');
            
            if (!email) {
                window.location.href = 'register-mock.php';
            }

            // DOM elements
            const displayEmail = document.getElementById('displayEmail');
            const otpBoxes = document.querySelectorAll('.otp-box');
            const verifyBtn = document.getElementById('verifyBtn');
            const resendBtn = document.getElementById('resendBtn');
            const timerText = document.getElementById('timerText');
            const verificationMessage = document.getElementById('verificationMessage');

            // Display email
            if (displayEmail) displayEmail.textContent = email;

            // State
            let resendTimerInterval = null;
            const RESEND_COOLDOWN = 59;
            let seconds = RESEND_COOLDOWN;

            // OTP input handling
            otpBoxes.forEach((box, idx) => {
                box.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                    if (e.target.value.length === 1 && idx < 5) {
                        otpBoxes[idx + 1].focus();
                    }
                });

                box.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && idx > 0) {
                        otpBoxes[idx - 1].focus();
                    }
                });

                box.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    if (/^\d{6}$/.test(paste)) {
                        otpBoxes.forEach((box, i) => box.value = paste[i]);
                        otpBoxes[5].focus();
                    }
                });
            });

            // Timer functions
            function updateTimer() {
                timerText.textContent = `Resend code in ${seconds} seconds`;
                resendBtn.disabled = true;
            }

            function resetTimer() {
                if (resendTimerInterval) clearInterval(resendTimerInterval);
                seconds = RESEND_COOLDOWN;
                updateTimer();

                resendTimerInterval = setInterval(() => {
                    seconds--;
                    if (seconds <= 0) {
                        clearInterval(resendTimerInterval);
                        resendTimerInterval = null;
                        timerText.textContent = 'Ready to resend';
                        resendBtn.disabled = false;
                    } else {
                        updateTimer();
                    }
                }, 1000);
            }

            // Start timer
            resetTimer();

            // Show message function
            function showMessage(text, type = 'success') {
                verificationMessage.className = `form-message ${type}`;
                verificationMessage.innerHTML = text;
            }

            // Verify OTP
            async function verifyOtp() {
                const token = Array.from(otpBoxes).map(b => b.value).join('');
                if (token.length !== 6) {
                    showMessage('Please enter the complete 6-digit code.', 'error');
                    return;
                }

                verifyBtn.disabled = true;
                verifyBtn.textContent = 'Verifying...';

                try {
                    // Get stored OTP from session
                    const storedOtp = sessionStorage.getItem('pending_otp');
                    const storedEmail = sessionStorage.getItem('pending_email');

                    if (!storedOtp || !storedEmail) {
                        throw new Error('Session expired. Please register again.');
                    }

                    if (email !== storedEmail) {
                        throw new Error('Email mismatch. Please register again.');
                    }

                    if (token === storedOtp) {
                        showMessage('✅ Email verified successfully! Redirecting...', 'success');
                        
                        // Update user metadata in Supabase
                        try {
                            const { error: updateError } = await supabase.auth.updateUser({
                                data: { email_verified: true }
                            });
                            if (updateError) console.warn('Meta update warning:', updateError);
                        } catch (updateErr) {
                            console.warn('Could not update user meta:', updateErr);
                        }

                        // Clear session
                        sessionStorage.removeItem('pending_email');
                        sessionStorage.removeItem('pending_otp');
                        sessionStorage.removeItem('pending_first_name');
                        sessionStorage.removeItem('pending_last_name');
                        sessionStorage.removeItem('pending_referral_code');

                        setTimeout(() => {
                            window.location.href = 'login-mock.php?verified=true';
                        }, 2000);
                    } else {
                        throw new Error('Invalid verification code. Please try again.');
                    }

                } catch (err) {
                    showMessage(err.message, 'error');
                    verifyBtn.disabled = false;
                    verifyBtn.textContent = 'Verify Email';
                }
            }

            // Resend OTP
            async function resendOtp() {
                resendBtn.disabled = true;

                try {
                    // Generate new OTP
                    const newOtp = Math.floor(100000 + Math.random() * 900000).toString();
                    
                    // Update session
                    sessionStorage.setItem('pending_otp', newOtp);
                    
                    // Show new OTP
                    alert(`New verification code: ${newOtp}`);
                    
                    showMessage('✅ New verification code generated. Check the alert.', 'success');
                    
                    // Clear OTP inputs
                    otpBoxes.forEach(box => box.value = '');
                    otpBoxes[0].focus();
                    
                    // Reset timer
                    resetTimer();

                } catch (err) {
                    showMessage(err.message, 'error');
                    resendBtn.disabled = false;
                }
            }

            // Event listeners
            verifyBtn.addEventListener('click', verifyOtp);
            resendBtn.addEventListener('click', resendOtp);

            // Enter key in last OTP box
            otpBoxes[5].addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    verifyOtp();
                }
            });

            // Cleanup timer on page unload
            window.addEventListener('beforeunload', () => {
                if (resendTimerInterval) clearInterval(resendTimerInterval);
            });

            // Log for debugging
            console.log('Current email:', email);
            console.log('Stored OTP:', sessionStorage.getItem('pending_otp'));
        })();
    </script>
</body>
</html>