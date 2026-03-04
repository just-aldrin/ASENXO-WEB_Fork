<?php
// test-email.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Email</title>
</head>
<body>
    <h2>Test Email Sending</h2>
    <form id="testForm">
        <input type="email" id="email" placeholder="Enter email" required>
        <button type="submit">Send Test Email</button>
    </form>
    <div id="result"></div>

    <script>
    document.getElementById('testForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const otp = Math.floor(100000 + Math.random() * 900000).toString();
        
        document.getElementById('result').innerHTML = 'Sending...';
        
        try {
            const response = await fetch('send-otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    email: email, 
                    otp: otp,
                    firstName: 'Test',
                    lastName: 'User'
                })
            });
            
            const result = await response.json();
            document.getElementById('result').innerHTML = JSON.stringify(result, null, 2);
        } catch (err) {
            document.getElementById('result').innerHTML = 'Error: ' + err.message;
        }
    });
    </script>
</body>
</html>