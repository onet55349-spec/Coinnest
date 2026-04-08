<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if (isset($_GET['registered'])) {
    $success = "Registration successful! Please log in.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'] ?: $user['first_name'];
        $_SESSION['role'] = $user['role'];
        
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email/username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | CoinNest</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="bg-container"></div>

    <div class="reg-wrapper">
        <div class="reg-card" style="max-width: 400px; padding: 40px 30px;">
            <a href="index.php" class="close-btn"><i class="ph-bold ph-x"></i></a>
            
            <div class="reg-header">
                <span class="reg-logo">CoinNest</span>
                <p style="font-size: 14px; opacity: 0.6; margin-top: 10px; color: #fff;">Log in or create an account to continue.</p>
            </div>

            <?php if ($error): ?>
                <div style="background: rgba(255, 71, 87, 0.1); color: #ff4757; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid rgba(255, 71, 87, 0.2);">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div style="background: rgba(46, 213, 115, 0.1); color: #2ed573; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid rgba(46, 213, 115, 0.2);">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div class="auth-tabs">
                <div class="auth-tab active">Log In</div>
                <a href="register.php" class="auth-tab">Register</a>
            </div>

            <form class="reg-form" action="login.php" method="POST">
                <div class="input-group">
                    <div class="input-field input-with-icon">
                        <i class="ph ph-envelope"></i>
                        <input type="text" name="email" placeholder="Email or Username" required>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-field input-with-icon">
                        <i class="ph ph-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                        <a href="#" class="field-link">Forgot password?</a>
                    </div>
                </div>

                <label class="reg-checkbox" style="margin-top: 5px;">
                    <input type="checkbox">
                    <span style="font-size: 14px; font-weight: 500;">Remember me</span>
                </label>

                <!-- I'm not a robot (reCAPTCHA) -->
                <div class="captcha-container" style="margin: 20px 0;">
                    <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" data-theme="dark"></div>
                </div>

                <button type="submit" class="cta-btn primary-cta" style="width: 100%; border: none; cursor: pointer; margin-top: 10px; padding: 18px;">Log In</button>
            </form>

            <div class="auth-divider">
                <span>OR</span>
            </div>

            <a href="#" class="google-btn">
                <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" alt="Google Logo">
                Log in with Google
            </a>

            <div class="reg-footer" style="margin-top: 30px;">
                New to CoinNest? <a href="register.php">Create an account</a>
            </div>
        </div>
    </div>
</body>
</html>
