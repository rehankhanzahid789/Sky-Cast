<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? htmlspecialchars($_SESSION['username']) : '';

// Determine active page
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyCast — Premium Weather</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body data-page="<?= $currentPage ?>">

<nav class="skycast-nav">
    <div class="nav-inner">
        <a href="index.php" class="nav-logo">
            <span class="logo-sky">Sky</span><span class="logo-cast">Cast</span>
            <span class="logo-dot">●</span>
        </a>
        <div class="nav-links">
            <a href="index.php" class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>">Home</a>
            <a href="forecast.php" class="nav-link <?= $currentPage === 'forecast' ? 'active' : '' ?>">Forecast</a>
            <?php if ($isLoggedIn): ?>
                <a href="dashboard.php" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                <a href="logout.php" class="nav-link nav-logout">Logout</a>
                <span class="nav-user">👤 <?= $username ?></span>
            <?php else: ?>
                <a href="login.php" class="nav-link <?= $currentPage === 'login' ? 'active' : '' ?>">Login</a>
                <a href="register.php" class="nav-btn">Get Started</a>
            <?php endif; ?>
        </div>
        <button class="nav-mobile-toggle" onclick="toggleMobileNav()">☰</button>
    </div>
    <div class="mobile-nav" id="mobileNav">
        <a href="index.php" class="nav-link">Home</a>
        <a href="forecast.php" class="nav-link">Forecast</a>
        <?php if ($isLoggedIn): ?>
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="logout.php" class="nav-link">Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-link">Login</a>
            <a href="register.php" class="nav-link">Register</a>
        <?php endif; ?>
    </div>
</nav>
