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
    $email    = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!$username || !$email || !$password || !$confirm) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (!DB_AVAILABLE) {
        $error = 'Database not configured. Please set up MySQL.';
    } else {
        global $pdo;
        // Check existing
        $check = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $check->execute([$username, $email]);
        if ($check->fetch()) {
            $error = 'Username or email already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $ins  = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
            $ins->execute([$username, $email, $hash]);

            $success = 'Account created successfully! You can now login.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — SkyCast</title>
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
            <a href="login.php" class="nav-btn">Login</a>
        </div>
    </div>
</nav>

<div class="auth-page">
    <div class="auth-card" style="max-width:520px;">
        <div class="auth-header">
            <h1>Join SkyCast</h1>
            <p>Create your free account today</p>
        </div>
        <div class="auth-body">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="login.php" style="color:var(--blue-grey);font-weight:600;">Login →</a></div>
            <?php endif; ?>
            <?php if (!DB_AVAILABLE): ?>
                <div class="alert alert-info">🔧 Database not connected. Registration requires MySQL. You can still browse the app.</div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-input"
                        placeholder="skycast_user"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        required minlength="3" maxlength="50"
                    />
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="you@example.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
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
                        placeholder="Min. 6 characters"
                        required minlength="6"
                    />
                </div>
                <div class="form-group">
                    <label class="form-label" for="confirm_password">Confirm Password</label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="form-input"
                        placeholder="Repeat your password"
                        required
                    />
                </div>
                <button type="submit" class="form-btn">Create Account →</button>
            </form>
            <p class="form-link">
                Already have an account? <a href="login.php">Sign in</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
