<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, address, state, password) VALUES (?, ?, ?, ?, ?, ?)");
            try {
                $stmt->execute([$first_name, $last_name, $email, $address, $state, $hashed_password]);
                $success = "Registration successful! You can now log in.";
                header("Location: login.php?registered=1");
                exit();
            } catch (PDOException $e) {
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Account | CoinNest</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="bg-container"></div>

    <div class="reg-wrapper">
        <div class="reg-card">
            <a href="index.php" class="close-btn"><i class="ph-bold ph-x"></i></a>
            
            <div class="reg-header">
                <span class="reg-logo">CoinNest</span>
                <h2 class="reg-subtitle">Create Your Account</h2>
            </div>

            <?php if ($error): ?>
                <div style="background: rgba(255, 71, 87, 0.1); color: #ff4757; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid rgba(255, 71, 87, 0.2);">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form class="reg-form" action="register.php" method="POST">
                <div class="input-row">
                    <div class="input-group">
                        <label>First Name</label>
                        <div class="input-field">
                            <input type="text" name="first_name" placeholder="First Name" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Last Name</label>
                        <div class="input-field">
                            <input type="text" name="last_name" placeholder="Last Name" required>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label>Email Address</label>
                    <div class="input-field input-with-icon">
                        <i class="ph ph-envelope"></i>
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label>Password</label>
                        <div class="input-field input-with-icon">
                            <i class="ph ph-lock"></i>
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Confirm Password</label>
                        <div class="input-field">
                            <input type="password" name="confirm_password" placeholder="Confirm" required>
                        </div>
                    </div>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label>Address</label>
                        <div class="input-field">
                            <input type="text" name="address" placeholder="Address" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>State</label>
                        <div class="input-field">
                            <select name="state" required>
                                <option value="" disabled selected>Select State</option>
                                <option value="NY">New York</option>
                                <option value="CA">California</option>
                                <option value="TX">Texas</option>
                                <option value="FL">Florida</option>
                            </select>
                            <i class="ph ph-caret-down" style="left: auto; right: 14px;"></i>
                        </div>
                    </div>
                </div>

                <div class="checkbox-container">
                    <label class="reg-checkbox">
                        <input type="checkbox" required>
                        <span>I agree to the <a href="#">Terms of Service</a> & <a href="#">Privacy Policy</a></span>
                    </label>
                    <label class="reg-checkbox">
                        <input type="checkbox">
                        <span>I would like to receive news and updates</span>
                    </label>
                </div>

                <button type="submit" class="cta-btn primary-cta" style="width: 100%; border: none; cursor: pointer; margin-top: 10px;">Sign Up</button>
            </form>

            <div class="reg-footer">
                Already have an account? <a href="login.php">Log in</a>
            </div>
        </div>
    </div>
</body>
</html>
