<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Find the admin user
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
$stmt->execute();
$user = $stmt->fetch();

if ($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'] ?: $user['first_name'];
    $_SESSION['role'] = $user['role'];
    
    header("Location: admin.php");
    exit();
} else {
    die("No admin user found. Please ensure the database is initialized.");
}
?>
