<?php require 'includes/header.php'; ?>

<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
    <div class="loading-text">Fetching Weather...</div>
</div>

<!-- HERO -->
<section class="hero" data-page="hero">
    <div class="hero-badge">✦ Premium Weather Intelligence</div>
    <h1 class="hero-title">
        <span class="line-blue">Read the Sky.</span>
        <span class="line-butter">Plan Your Day.</span>
    </h1>
    <p class="hero-subtitle">
        Real-time forecasts, 5-day outlooks, and hourly precision —
        beautifully rendered for the modern explorer.
    </p>

    <!-- Search -->
    <div class="search-container">
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input
                type="text"
                id="searchInput"
                class="search-input"
                placeholder="Search city... e.g. London, Tokyo"
                autocomplete="off"
            />
            <div class="unit-toggle">
                <button class="active" data-unit="metric">°C</button>
                <button data-unit="imperial">°F</button>
            </div>
            <button id="searchBtn" class="search-btn">Search</button>
        </div>
        <div id="searchSuggestions" class="search-suggestions"></div>
    </div>

    <button class="geo-btn" onclick="getMyLocation()">
        📍 Use My Location
    </button>

    <!-- Current Weather Display -->
    <div id="weatherDisplay" class="weather-display hidden" style="margin-top:3rem;position:relative;z-index:5;"></div>
</section>

<!-- HOURLY FORECAST -->
<section id="hourlySection" class="section section-blue hidden">
    <div class="container">
        <div class="section-header">
            <div class="section-tag tag-blue">Hour by Hour</div>
            <h2 class="section-title title-blue">Hourly Forecast</h2>
        </div>
        <div id="hourlyScroll" class="hourly-scroll"></div>
    </div>
</section>

<!-- 5-DAY FORECAST -->
<section id="forecastSection" class="section section-butter hidden">
    <div class="container">
        <div class="section-header">
            <div class="section-tag tag-butter">Looking Ahead</div>
            <h2 class="section-title title-butter">5-Day Forecast</h2>
        </div>
        <div id="forecastGrid" class="forecast-grid"></div>
        <div class="text-center mt-4">
            <a href="forecast.php" class="search-btn" style="display:inline-block;text-decoration:none;">
                View Full Forecast →
            </a>
        </div>
    </div>
</section>

<!-- SPLIT SECTION: Features -->
<section class="section-split">
    <div class="split-blue">
        <div class="section-tag tag-butter" style="color:rgba(243,217,143,0.7);margin-bottom:1rem;">Sky Intelligence</div>
        <h2 class="section-title" style="color:#0e1a2e;margin-bottom:1.5rem;">
            Weather data you<br>can actually trust.
        </h2>
        <p style="color:rgba(14,26,46,0.7);font-size:1.05rem;line-height:1.8;max-width:400px;">
            Powered by OpenWeatherMap's global sensor network.
            Over 40,000 weather stations, satellite data, and
            AI-enhanced precision — in the palm of your hand.
        </p>
        <div style="margin-top:2rem;display:flex;gap:1rem;flex-wrap:wrap;">
            <div style="background:rgba(14,26,46,0.12);border-radius:12px;padding:1rem 1.5rem;text-align:center;border:1px solid rgba(14,26,46,0.15);">
                <div style="font-size:1.8rem;font-weight:900;font-family:var(--font-display);color:#0e1a2e;">40k+</div>
                <div style="font-size:0.7rem;font-family:var(--font-mono);text-transform:uppercase;letter-spacing:1px;color:rgba(14,26,46,0.5);">Stations</div>
            </div>
            <div style="background:rgba(14,26,46,0.12);border-radius:12px;padding:1rem 1.5rem;text-align:center;border:1px solid rgba(14,26,46,0.15);">
                <div style="font-size:1.8rem;font-weight:900;font-family:var(--font-display);color:#0e1a2e;">5-day</div>
                <div style="font-size:0.7rem;font-family:var(--font-mono);text-transform:uppercase;letter-spacing:1px;color:rgba(14,26,46,0.5);">Forecast</div>
            </div>
            <div style="background:rgba(14,26,46,0.12);border-radius:12px;padding:1rem 1.5rem;text-align:center;border:1px solid rgba(14,26,46,0.15);">
                <div style="font-size:1.8rem;font-weight:900;font-family:var(--font-display);color:#0e1a2e;">3hr</div>
                <div style="font-size:0.7rem;font-family:var(--font-mono);text-transform:uppercase;letter-spacing:1px;color:rgba(14,26,46,0.5);">Intervals</div>
            </div>
        </div>
    </div>
    <div class="split-butter">
        <div class="section-tag" style="color:rgba(26,18,0,0.5);font-family:var(--font-mono);font-size:0.7rem;letter-spacing:3px;text-transform:uppercase;margin-bottom:1rem;">Explore Cities</div>
        <h2 class="section-title" style="color:#1a1200;margin-bottom:1.5rem;">
            Popular<br>destinations.
        </h2>
        <div style="display:flex;flex-direction:column;gap:0.8rem;">
            <?php
            $cities = [
                ['Islamabad', '🏔', 'Pakistan'],
                ['London', '🌧', 'United Kingdom'],
                ['Tokyo', '☀️', 'Japan'],
                ['New York', '🌆', 'United States'],
                ['Dubai', '🏜', 'UAE'],
            ];
            foreach ($cities as [$name, $icon, $country]):
            ?>
            <div onclick="window.location.href='forecast.php?city=<?= urlencode($name) ?>'"
                 style="
                    background:rgba(26,18,0,0.08);
                    border:1px solid rgba(26,18,0,0.12);
                    border-radius:12px;
                    padding:0.9rem 1.2rem;
                    display:flex;
                    align-items:center;
                    gap:0.8rem;
                    cursor:pointer;
                    transition:all 0.25s ease;
                 "
                 onmouseover="this.style.background='rgba(26,18,0,0.14)';this.style.transform='translateX(6px)'"
                 onmouseout="this.style.background='rgba(26,18,0,0.08)';this.style.transform='translateX(0)'"
            >
                <span style="font-size:1.4rem;"><?= $icon ?></span>
                <span style="font-weight:600;color:#1a1200;flex:1;"><?= $name ?></span>
                <span style="font-size:0.75rem;color:rgba(26,18,0,0.45);font-family:var(--font-mono);"><?= $country ?></span>
                <span style="color:rgba(26,18,0,0.3);">→</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="section section-blue">
    <div class="container">
        <div class="section-header">
            <div class="section-tag tag-blue">Everything You Need</div>
            <h2 class="section-title title-blue">Built for Weather Enthusiasts</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon-wrap">🌍</div>
                <div class="feature-title">Global Coverage</div>
                <div class="feature-desc">Search any city on Earth and get accurate real-time conditions instantly.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon-wrap">⭐</div>
                <div class="feature-title">Save Favorites</div>
                <div class="feature-desc">Create an account to save your most-visited cities for one-tap access.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon-wrap">📍</div>
                <div class="feature-title">GPS Location</div>
                <div class="feature-desc">Auto-detect your location and get hyperlocal weather without typing anything.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon-wrap">🕐</div>
                <div class="feature-title">Hourly Detail</div>
                <div class="feature-desc">Hour-by-hour breakdowns so you can plan every part of your day with confidence.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon-wrap">📅</div>
                <div class="feature-title">5-Day Outlook</div>
                <div class="feature-desc">Plan trips, events, and outdoor activities with 5-day extended forecasts.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon-wrap">📊</div>
                <div class="feature-title">Rich Metrics</div>
                <div class="feature-desc">Humidity, pressure, wind speed, visibility, UV index and more — all in one view.</div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS / QUOTE STRIP -->
<section style="background:linear-gradient(90deg,var(--butter) 0%,var(--butter-dark) 50%,var(--blue-grey) 100%);padding:3rem 2rem;text-align:center;">
    <div class="container">
        <p style="font-family:var(--font-display);font-size:clamp(1.4rem,3vw,2rem);font-weight:700;color:#0e1a2e;max-width:700px;margin:0 auto;line-height:1.5;letter-spacing:-0.5px;">
            "The sky is not the limit — it's where we begin."
        </p>
        <p style="margin-top:1rem;font-size:0.85rem;color:rgba(14,26,46,0.6);font-family:var(--font-mono);letter-spacing:2px;text-transform:uppercase;">SkyCast Premium Weather</p>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
