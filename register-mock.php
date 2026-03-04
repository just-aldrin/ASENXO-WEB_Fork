<?php
// register-mock.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | ASENXO</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="src/css/register-style.css">
    <style>
        .password-wrapper { position: relative; }
        .password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #888; }
        .password-checklist { list-style: none; padding: 10px; margin: 10px 0 0; background: #1a1a1a; border-radius: 8px; font-size: 13px; }
        .password-checklist li { color: #888; margin-bottom: 5px; }
        .password-checklist li.valid { color: #2ecc71; }
        .password-checklist li.valid:before { content: "✓ "; }
        .password-checklist li.invalid:before { content: "✗ "; }
        .form-message { margin-top: 1rem; padding: 0.75rem; border-radius: 8px; font-size: 0.9rem; display: none; }
        .form-message.success { background-color: rgba(46,204,113,0.15); color: #2ecc71; border: 1px solid rgba(46,204,113,0.3); display: block; }
        .form-message.error { background-color: rgba(231,76,60,0.15); color: #e74c3c; border: 1px solid rgba(231,76,60,0.3); display: block; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: #1a1a1a; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto; }
    </style>
</head>
<body>
    <main class="register-page">
        <div class="register-card">
            <div class="form-col">
                <a href="index.php"><img src="src/img/logo-name.png" class="form-logo" alt="ASENXO Logo"></a>

                <button class="google-btn" type="button" id="googleRegister">
                    <i class="fab fa-google"></i> Sign up with Google
                </button>

                <div class="divider">
                    <span class="divider-line"></span><span>or</span><span class="divider-line"></span>
                </div>

                <form id="registerForm">
                    <div class="row">
                        <div class="input-group">
                            <label>First Name*</label>
                            <input type="text" name="first_name" placeholder="Juan" required>
                        </div>
                        <div class="input-group">
                            <label>Last Name*</label>
                            <input type="text" name="last_name" placeholder="de la Cruz" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Email*</label>
                        <input type="email" name="email" placeholder="juan.delacruz@gmail.com" required>
                    </div>

                    <div class="input-group password-group">
                        <label>Password*</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="Enter your password" required>
                            <button type="button" class="password-toggle" id="togglePassword" tabindex="-1">
                                <i class="far fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        <ul class="password-checklist" id="passwordChecklist" style="display: none;">
                            <li id="checkUpper">Uppercase letter</li>
                            <li id="checkLower">Lowercase letter</li>
                            <li id="checkNumber">Number</li>
                            <li id="checkSpecial">Special character (e.g. !?<>@#$%)</li>
                            <li id="checkLength">8 characters or more</li>
                        </ul>
                    </div>

                    <div class="input-group">
                        <label>Referral Code (Optional)</label>
                        <input type="text" name="referral_code" value="ADM0004">
                    </div>

                    <div class="agree-row">
                        <input type="checkbox" name="terms" required checked>
                        <label>I agree to <span id="termsLink">Terms and Conditions</span></label>
                    </div>

                    <div id="formMessage" class="form-message"></div>

                    <button type="submit" class="signup-btn" id="signupBtn">Sign Up</button>
                </form>

                <div class="login-link">
                    Already have an account? <a href="login-mock.php">Log in</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Terms Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <h4 style="color: #e2b974; margin-bottom: 20px;">Terms and Conditions</h4>
            <div style="color: #ccc; line-height: 1.6;">
                <p>1. Acceptance of Terms</p>
                <p style="font-size: 14px; margin-bottom: 15px;">By accessing and using ASENXO services, you accept and agree to be bound by these terms.</p>
                
                <p>2. User Accounts</p>
                <p style="font-size: 14px; margin-bottom: 15px;">You are responsible for maintaining the confidentiality of your account credentials.</p>
                
                <p>3. Privacy</p>
                <p style="font-size: 14px; margin-bottom: 15px;">Your data is handled according to our Privacy Policy.</p>
                
                <p>4. Verification</p>
                <p style="font-size: 14px; margin-bottom: 15px;">You must verify your email address to activate your account.</p>
            </div>
            <button class="close-modal" id="closeModal" style="background: #e2b974; color: #000; border: none; padding: 10px 30px; border-radius: 5px; margin-top: 20px; cursor: pointer; width: 100%;">Close</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script>
    (function() {
        // Supabase client - CORRECT URL
        const SUPABASE_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
        const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw';
        const supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

        // DOM elements
        const form = document.getElementById('registerForm');
        const signupBtn = document.getElementById('signupBtn');
        const formMessage = document.getElementById('formMessage');
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const toggleIcon = document.getElementById('toggleIcon');
        const termsLink = document.getElementById('termsLink');
        const modal = document.getElementById('termsModal');
        const closeBtn = document.getElementById('closeModal');

        // Password toggle
        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', () => {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                toggleIcon.classList.toggle('fa-eye');
                toggleIcon.classList.toggle('fa-eye-slash');
            });
        }

        // Terms modal
        if (termsLink) {
            termsLink.addEventListener('click', (e) => {
                e.preventDefault();
                modal.style.display = 'flex';
            });
        }
        if (closeBtn) closeBtn.addEventListener('click', () => modal.style.display = 'none');
        window.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

        // Password Checklist
        const password = document.getElementById("password");
        const checklist = document.getElementById("passwordChecklist");
        if (password && checklist) {
            const checkUpper = document.getElementById("checkUpper");
            const checkLower = document.getElementById("checkLower");
            const checkNumber = document.getElementById("checkNumber");
            const checkSpecial = document.getElementById("checkSpecial");
            const checkLength = document.getElementById("checkLength");

            function toggle(el, valid) {
                el.classList.remove("valid", "invalid");
                el.classList.add(valid ? "valid" : "invalid");
            }

            password.addEventListener("input", () => {
                const val = password.value;
                if (val.length > 0 && checklist.style.display === 'none') {
                    checklist.style.display = 'block';
                } else if (val.length === 0 && checklist.style.display !== 'none') {
                    checklist.style.display = 'none';
                }
                toggle(checkUpper, /[A-Z]/.test(val));
                toggle(checkLower, /[a-z]/.test(val));
                toggle(checkNumber, /[0-9]/.test(val));
                toggle(checkSpecial, /[!@#$%^&*(),.?":{}|<>]/.test(val));
                toggle(checkLength, val.length >= 8);
            });
        }

        // Helper: show message
        function showMessage(text, type = 'success') {
            formMessage.className = `form-message ${type}`;
            formMessage.innerHTML = text;
        }

        // Generate random 6-digit OTP
        function generateOtp() {
            return Math.floor(100000 + Math.random() * 900000).toString();
        }

        // Handle form submit
        // register-mock.php (Update this section)
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;
    const firstName = document.querySelector('input[name="first_name"]').value;
    const lastName = document.querySelector('input[name="last_name"]').value;
    const referralCode = document.querySelector('input[name="referral_code"]').value;

    signupBtn.disabled = true;
    signupBtn.textContent = 'Creating account...';

    try {
        // 1. Register with Supabase Auth
        const { data: authData, error: authError } = await supabase.auth.signUp({
            email,
            password,
            options: { 
                data: { first_name: firstName, last_name: lastName, referral_code: referralCode }
            }
        });

        if (authError) throw authError;

        // 2. Generate OTP and Expiry (10 minutes)
        const generatedOtp = Math.floor(100000 + Math.random() * 900000).toString();
        const expiresAt = new Date(Date.now() + 10 * 60000).toISOString();

        // 3. CRITICAL: Save OTP to the DATABASE table
        const { error: dbError } = await supabase
            .from('email_verifications')
            .upsert({ 
                email: email, 
                otp: generatedOtp, 
                expires_at: expiresAt,
                attempts: 0 
            }, { onConflict: 'email' });

        if (dbError) {
            console.error('Database Error:', dbError);
            throw new Error('Failed to save verification code. Please try again.');
        }

        // 4. Send the Email
        try {
            await fetch('send-otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    email: email, 
                    otp: generatedOtp,
                    firstName: firstName
                })
            });
        } catch (emailErr) {
            console.warn('Email delivery failed, but code is in DB:', emailErr);
        }

        // 5. Success UI and Redirect
        alert(`Your verification code is: ${generatedOtp}`);
        showMessage('Account created! Redirecting to verification...', 'success');
        
        setTimeout(() => {
            window.location.href = 'verification.php?email=' + encodeURIComponent(email);
        }, 1500);

    } catch (err) {
        console.error('Registration error:', err);
        showMessage(err.message || 'Error creating account', 'error');
        signupBtn.disabled = false;
        signupBtn.textContent = 'Sign Up';
    }
});
    })();
    </script>
</body>
</html>