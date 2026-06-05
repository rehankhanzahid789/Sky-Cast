<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId   = (int)$_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username']);

$savedCities = [];
$history     = [];
$cityCount   = 0;
$searchCount = 0;

if (DB_AVAILABLE && $pdo) {
    // Saved cities
    $stmt = $pdo->prepare('SELECT id, city_name, country_code, added_at FROM saved_cities WHERE user_id = ? ORDER BY added_at DESC');
    $stmt->execute([$userId]);
    $savedCities = $stmt->fetchAll();
    $cityCount   = count($savedCities);

    // History
    $stmt2 = $pdo->prepare('SELECT city_name, temperature, condition_text, humidity, wind_speed, searched_at FROM weather_history WHERE user_id = ? ORDER BY searched_at DESC LIMIT 15');
    $stmt2->execute([$userId]);
    $history     = $stmt2->fetchAll();
    $searchCount = count($history);
}
?>
<?php require 'includes/header.php'; ?>

<div class="dashboard-page">

    <!-- Hero Banner -->
    <div class="dashboard-hero">
        <div class="dashboard-hero-inner">
            <div class="dashboard-greeting">
                <h1>Good <?= (date('H') < 12) ? 'Morning' : ((date('H') < 17) ? 'Afternoon' : 'Evening') ?>, <?= $username ?> ☀️</h1>
                <p><?= date('l, F j, Y') ?> · Your personal weather hub</p>
            </div>
            <div class="dashboard-stats">
                <div class="dash-stat">
                    <div class="dash-stat-num"><?= $cityCount ?></div>
                    <div class="dash-stat-label">Saved Cities</div>
                </div>
                <div class="dash-stat">
                    <div class="dash-stat-num"><?= $searchCount ?></div>
                    <div class="dash-stat-label">Searches</div>
                </div>
                <div class="dash-stat">
                    <div class="dash-stat-num"><?= date('H:i') ?></div>
                    <div class="dash-stat-label">Local Time</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Search -->
    <div style="background:linear-gradient(135deg,var(--ink-mid),var(--ink));padding:2rem;">
        <div class="container" style="max-width:700px;">
            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" class="search-input" placeholder="Quick search any city..." autocomplete="off" />
                <div class="unit-toggle">
                    <button class="active" data-unit="metric">°C</button>
                    <button data-unit="imperial">°F</button>
                </div>
                <button id="searchBtn" class="search-btn">Search</button>
            </div>
            <div id="searchSuggestions" class="search-suggestions" style="position:relative;"></div>
            <div id="weatherDisplay" class="weather-display hidden" style="margin-top:1.5rem;"></div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-content">

        <!-- Saved Cities -->
        <div class="dash-panel">
            <div class="dash-section-title">⭐ Saved Cities</div>
            <?php if (empty($savedCities)): ?>
                <div class="empty-state">
                    <div class="empty-icon">🌍</div>
                    <p>No saved cities yet.<br>Search for a city and click "Save City" to add it here.</p>
                    <?php if (!DB_AVAILABLE): ?>
                        <p style="margin-top:0.5rem;font-size:0.8rem;color:rgba(243,217,143,0.3);">Note: MySQL required to save cities.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="cities-grid">
                    <?php foreach ($savedCities as $sc): ?>
                    <div class="city-chip" onclick="cityClick('<?= htmlspecialchars($sc['city_name']) ?>')">
                        <div>
                            <div class="city-chip-name"><?= htmlspecialchars($sc['city_name']) ?></div>
                            <div class="city-chip-meta"><?= htmlspecialchars($sc['country_code']) ?></div>
                        </div>
                        <button
                            class="city-chip-remove"
                            onclick="event.stopPropagation(); removeCity(<?= (int)$sc['id'] ?>, this)"
                            title="Remove"
                        >✕</button>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Search History -->
        <div class="dash-panel">
            <div class="dash-section-title">🕐 Recent Searches</div>
            <?php if (empty($history)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <p>No search history yet.<br>Start searching for cities to build your history.</p>
                </div>
            <?php else: ?>
                <div style="overflow-x:auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>City</th>
                                <th>Temp</th>
                                <th>Condition</th>
                                <th>Humidity</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $h): ?>
                            <tr>
                                <td style="font-weight:600;color:var(--butter);"><?= htmlspecialchars($h['city_name']) ?></td>
                                <td><?= $h['temperature'] !== null ? round($h['temperature']) . '°C' : '—' ?></td>
                                <td><?= htmlspecialchars($h['condition_text'] ?? '—') ?></td>
                                <td><?= $h['humidity'] !== null ? $h['humidity'] . '%' : '—' ?></td>
                                <td style="font-family:var(--font-mono);font-size:0.75rem;">
                                    <?= date('M j, H:i', strtotime($h['searched_at'])) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Weather for Saved Cities -->
    <?php if (!empty($savedCities)): ?>
    <section class="section section-blue" style="padding:3rem 2rem;">
        <div class="container">
            <div class="section-header">
                <div class="section-tag tag-blue">Your Collection</div>
                <h2 class="section-title title-blue">Favorite Destinations</h2>
            </div>
            <div class="cities-grid">
                <?php foreach ($savedCities as $sc): ?>
                <div class="city-chip" onclick="cityClick('<?= htmlspecialchars($sc['city_name']) ?>')" style="cursor:pointer;">
                    <div>
                        <div class="city-chip-name"><?= htmlspecialchars($sc['city_name']) ?></div>
                        <div class="city-chip-meta">Click to view forecast →</div>
                    </div>
                    <span style="font-size:1.4rem;">🌍</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

</div>

<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
    <div class="loading-text">Fetching Weather...</div>
</div>

<?php require 'includes/footer.php'; ?>
