<?php
require 'includes/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(strip_tags($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = 'Please fill in all fields.';
    } elseif (!DB_AVAILABLE) {
        // Demo login for portfolio
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        global $pdo;
        $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SkyCast</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="skycast-nav">
    <div class="nav-inner">
        <a href="index.php" class="nav-logo">
            <span class="logo-sky">Sky</span><span class="logo-cast">Cast</span>
        </a>
        <div class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="register.php" class="nav-btn">Register</a>
        </div>
    </div>
</nav>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p>Sign in to your SkyCast account</p>
        </div>
        <div class="auth-body">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (!DB_AVAILABLE): ?>
                <div class="alert alert-info">🔧 Demo mode: DB not connected. Any credentials will log you in.</div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label class="form-label" for="username">Username or Email</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-input"
                        placeholder="your_username"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        required
                    />
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="••••••••"
                        required
                    />
                </div>
                <button type="submit" class="form-btn">Sign In →</button>
            </form>
            <p class="form-link">
                Don't have an account? <a href="register.php">Create one</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
