<?php
  // In verification.php
  $email = $_GET['email'] ?? ''; 
?>
<input type="hidden" id="email" value="<?php echo htmlspecialchars($email); ?>">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASENXO | Verify email</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Bricolage Grotesque + fallback (Inter as secondary) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Bricolage Grotesque (variable) -->
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700&display=swap" rel="stylesheet">

    
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="src/css/verification-style.css">
    <style>
        @keyframes cardIntro {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .register-card {
            animation: cardIntro 0.7s cubic-bezier(0.2, 0.9, 0.3, 1) forwards;
            transform-origin: center;
            will-change: transform, opacity;
        }
    
        body {
            animation: bodyFade 0.9s ease-out;
        }
        @keyframes bodyFade {
            0% { background-color: #000; }
            100% { background-color: #0a0a0a; }
        }

        .register-card {
            background: var(--bg-card, #0e0e0e);
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="form-col">
        
            <div class="verify-header">
                <a href="login-mock.php" class="back-link" aria-label="Go back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <a href="index.php"><img src="src/img/logo-name.png" class="logo-img" alt="ASENXO Logo"></a>
            </div>

            <div class="mail-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m0 8V6a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2z" />
                </svg>
            </div>

            <!-- main content -->
            <h2>Verify your email address</h2>
            <p class="description">
                To start using your ASENXO account, you need to confirm your email address sent to
            </p>
            <div class="email-highlight">
                useremail@gmail.com
            </div>

            <!-- resend action -->
            <div class="resend-section">
                <span>Didn't get the email?</span>
                <button class="resend-link" onclick="alert('Resend email triggered')">Resend Email</button>
            </div>
        </div>
    </div>
    <!-- fixed: removed stray closing tag </a> that was outside body -->
</body>
</html>