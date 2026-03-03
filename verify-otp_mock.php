<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); width: 100%; max-width: 400px; }
        h2 { margin-top: 0; color: #333; font-size: 1.5rem; text-align: center; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; color: #666; font-size: 0.9rem; }
        input { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 1rem; }
        input:focus { outline: none; border-color: #007bff; ring: 2px solid #007bff; }
        button { width: 100%; padding: 0.75rem; background-color: #007bff; color: white; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; transition: background 0.2s; }
        button:hover { background-color: #0056b3; }
        #message { margin-top: 1rem; padding: 0.75rem; border-radius: 6px; display: none; text-align: center; font-size: 0.9rem; }
        .error { background-color: #f8d7da; color: #721c24; display: block !important; }
        .success { background-color: #d4edda; color: #155724; display: block !important; }
    </style>
</head>
<body>

<div class="card">
    <h2>Verify & Register</h2>
    <form id="registrationForm">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" id="email" required placeholder="name@example.com">
        </div>
        <div class="form-group">
            <label>OTP Code</label>
            <input type="text" id="otp" required placeholder="Enter verification code">
        </div>
        <div style="display: flex; gap: 10px;">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" id="first_name" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" id="last_name" required>
            </div>
        </div>
        <div class="form-group">
            <label>Referral Code (Optional)</label>
            <input type="text" id="referral_code">
        </div>
        <button type="submit" id="submitBtn">Verify Account</button>
    </form>
    <div id="message"></div>
</div>

<script>
    const form = document.getElementById('registrationForm');
    const msgDiv = document.getElementById('message');
    const btn = document.getElementById('submitBtn');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Reset UI
        msgDiv.className = '';
        msgDiv.textContent = 'Processing...';
        msgDiv.style.display = 'block';
        btn.disabled = true;

        const formData = {
            email: document.getElementById('email').value,
            otp: document.getElementById('otp').value,
            first_name: document.getElementById('first_name').value,
            last_name: document.getElementById('last_name').value,
            referral_code: document.getElementById('referral_code').value
        };

        try {
            // REPLACE 'your_script_name.php' with the actual filename of your PHP code
            const response = await fetch('verify-otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                msgDiv.className = 'success';
                msgDiv.textContent = 'Registration successful! Redirecting...';
                form.reset();
                // Optional: window.location.href = '/dashboard';
            } else {
                msgDiv.className = 'error';
                msgDiv.textContent = result.error || 'An unknown error occurred.';
            }
        } catch (err) {
            msgDiv.className = 'error';
            msgDiv.textContent = 'Connection failed. Please check your server.';
        } finally {
            btn.disabled = false;
        }
    });
</script>

</body>
</html>