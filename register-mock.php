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
</head>
<body>
    <main class="register-page">
        <div class="register-card">
            <div class="form-col">
                <a href="index.php"><img src="src/img/logo-name.png" class="form-logo" alt="ASENXO Logo"></a>

                <button class="google-btn" type="button">
                    <i class="fab fa-google"></i> Login with Google
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
                        <input type="text" name="referral_code">
                    </div>

                    <div class="agree-row">
                        <input type="checkbox" name="terms" required>
                        <label>I agree to <span id="termsLink">Terms and Conditions</span></label>
                    </div>

                    <div id="formMessage" class="form-message"></div>

                    <button type="submit" class="signup-btn" id="signupBtn">Sign Up</button>
                </form>

                <!-- OTP section will be injected here -->

                <div class="login-link">
                    Already have an account? <a href="login-mock.php">Log in</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Terms Modal (keep your existing modal content) -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <h4>Terms and Conditions</h4>
            <p>...</p>
            <button class="close-modal" id="closeModal">Close</button>
        </div>
    </div>

    <!-- Supabase JS library -->
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script>
        (function() {
            // ---------- Supabase client ----------
            const SUPABASE_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
            const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw';
            const supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

            // ---------- DOM elements ----------
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

            // ---------- Password Checklist ----------
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

            // ---------- State ----------
            let pendingEmail = null;
            let pendingOtp = null; // store the generated OTP
            let resendTimerInterval = null;
            const RESEND_COOLDOWN = 59;

            // ---------- Helper: show message ----------
            function showMessage(text, type = 'success') {
                formMessage.className = `form-message ${type}`;
                formMessage.innerHTML = text;
            }

            // ---------- Helper: clear OTP UI ----------
            function removeOtpUI() {
                const existing = document.getElementById('otpSection');
                if (existing) existing.remove();
                if (resendTimerInterval) clearInterval(resendTimerInterval);
            }

            // ---------- Generate random 6-digit OTP ----------
            // const generatedOtp = Math.floor(100000 + Math.random() * 900000).toString();
            // function generatedOtp() {
            //    return Math.floor(100000 + Math.random() * 900000).toString();
            // }

            // ---------- Send OTP via your backend (PHPMailer) ----------
            async function sendOtpEmail(email, otp) {
            const response = await fetch('send-otp.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, otp })
                });
                const data = await response.json();
                if (!data.success) {
                    throw new Error(data.error || 'Failed to send OTP email');
                }
                return data;
            }

            // ---------- Create OTP input section ----------
            function createOtpSection() {
                removeOtpUI();
                const section = document.createElement('div');
                section.id = 'otpSection';
                section.className = 'otp-container';
                section.innerHTML = `
                    <p style="color: #ccc; text-align: center;">Enter the 6‑digit code sent to your email</p>
                    <div class="otp-inputs" id="otpInputs">
                        ${Array(6).fill(0).map(() => '<input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">').join('')}
                    </div>
                    <div style="display: flex; gap: 10px; justify-content: center; margin-top: 15px;">
                        <button type="button" id="verifyOtpBtn" class="signup-btn" style="flex:1;">Verify OTP</button>
                        <button type="button" id="resendOtpBtn" class="signup-btn" style="flex:1; background:transparent; border:1px solid #e2b974;">Resend OTP</button>
                    </div>
                    <div style="text-align: center; margin-top: 10px;">
                        <span class="resend-timer" id="resendTimer">Resend code in ${RESEND_COOLDOWN} seconds</span>
                    </div>
                `;
                form.parentNode.insertBefore(section, form.nextSibling);

                const otpBoxes = document.querySelectorAll('.otp-box');
                otpBoxes.forEach((box, idx) => {
                    box.addEventListener('input', (e) => {
                        e.target.value = e.target.value.replace(/[^0-9]/g, '');
                        if (e.target.value.length === 1 && idx < 5) otpBoxes[idx + 1].focus();
                    });
                    box.addEventListener('keydown', (e) => {
                        if (e.key === 'Backspace' && !e.target.value && idx > 0) otpBoxes[idx - 1].focus();
                    });
                });

                document.getElementById('otpInputs').addEventListener('paste', (e) => {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    if (/^\d{6}$/.test(paste)) {
                        otpBoxes.forEach((box, i) => box.value = paste[i]);
                        otpBoxes[5].focus();
                    }
                });

                document.getElementById('verifyOtpBtn').addEventListener('click', verifyOtp);

                const resendBtn = document.getElementById('resendOtpBtn');
                const timerSpan = document.getElementById('resendTimer');
                let seconds = RESEND_COOLDOWN;

                function updateTimer() {
                    timerSpan.textContent = `Resend code in ${seconds} seconds`;
                    timerSpan.classList.add('disabled');
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
                            timerSpan.textContent = 'Resend code';
                            timerSpan.classList.remove('disabled');
                            resendBtn.disabled = false;
                        } else {
                            updateTimer();
                        }
                    }, 1000);
                }

                resetTimer();

                resendBtn.addEventListener('click', async () => {
                    if (resendBtn.disabled) return;
                    resendBtn.disabled = true;
                    try {
                        // Generate new OTP and send it
                        const newOtp = generateOtp();
                        pendingOtp = newOtp;
                        await sendOtpEmail(pendingEmail, newOtp);
                        showMessage(`A new OTP has been sent to ${pendingEmail}.`, 'success');
                        resetTimer();
                    } catch (err) {
                        showMessage(err.message, 'error');
                        resendBtn.disabled = false;
                    }
                });
            }

            // ---------- Verify OTP ----------
            async function verifyOtp() {
                const otpBoxes = document.querySelectorAll('.otp-box');
                const token = Array.from(otpBoxes).map(b => b.value).join('');
                if (token.length !== 6) {
                    showMessage('Please enter the complete 6‑digit OTP.', 'error');
                    return;
                }

                const verifyBtn = document.getElementById('verifyOtpBtn');
                verifyBtn.disabled = true;
                verifyBtn.textContent = 'Verifying...';

                try {
                    const response = await fetch('verify-otp.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            email: pendingEmail,
                            otp: token,
                            first_name: document.querySelector('input[name="first_name"]').value,
                            last_name: document.querySelector('input[name="last_name"]').value,
                            referral_code: document.querySelector('input[name="referral_code"]').value
                        })
                    });
                    const data = await response.json();
                    if (!data.success) throw new Error(data.error);

                    showMessage('✅ Email verified! Redirecting to login...', 'success');
                    form.reset();
                    removeOtpUI();
                    signupBtn.disabled = false;
                    signupBtn.textContent = 'Sign Up';
                    setTimeout(() => window.location.href = 'login-mock.php', 2000);
                } catch (err) {
                    showMessage(err.message, 'error');
                    verifyBtn.disabled = false;
                    verifyBtn.textContent = 'Verify OTP';
                }
            }

            // ---------- Handle form submit (sign up) ----------
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const email = document.querySelector('input[name="email"]').value;
                const password = document.querySelector('input[name="password"]').value;
                const firstName = document.querySelector('input[name="first_name"]').value;
                const lastName = document.querySelector('input[name="last_name"]').value;
                const referralCode = document.querySelector('input[name="referral_code"]').value;

                if (password.length < 8) {
                    showMessage('Password must be at least 8 characters.', 'error');
                    return;
                }

                signupBtn.disabled = true;
                signupBtn.textContent = 'Creating account...';

                const generatedOtp = Math.floor(100000 + Math.random() * 900000).toString();

                try {
                    // Build metadata object
                    const metadata = { first_name: firstName, last_name: lastName };
                    if (referralCode) metadata.referral_code = referralCode;

                    const { data, error } = await supabase.auth.signUp({
                        email,
                        password,
                        options: { 
                            data: metadata,
                            emailRedirectTo: window.location.origin + '/ASENXO-WEB-Fork/verify-otp_mock.php'
                        }
                    });

                    if (error) throw error;

                        pendingEmail = email;
                        // const otp = generatedOtp();

                        // Change 'send-otp.php' to './send-otp.php'
                        const response = await fetch('./send-otp.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ 
                                email: email, 
                                otp: generatedOtp // This variable is now defined!
                            })
                        });

                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            const data = await response.json();
                            if (!data.success) throw new Error(data.error || 'Failed to send OTP');
                        } else {
                            const textError = await response.text();
                            console.error("Server returned HTML instead of JSON:", textError);
                            throw new Error("Server Error: Check your PHP logs or Network tab.");
                        }

                        window.location.href = 'verification.php?email=' + encodeURIComponent(email);

                    } catch (err) {
                        showMessage(err.message, 'error');
                        signupBtn.disabled = false;
                        signupBtn.textContent = 'Sign Up';
                    }
            });
        })();
    </script>
</body>
</html>