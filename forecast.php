<?php require 'includes/header.php'; ?>

<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
    <div class="loading-text">Loading Forecast...</div>
</div>

<div class="forecast-page" data-page="forecast">

    <!-- Search Strip -->
    <div class="forecast-search-bar">
        <div class="forecast-search-inner">
            <h2>Extended Weather Forecast</h2>
            <div class="search-wrap" style="max-width:560px;margin:0 auto;">
                <span class="search-icon" style="color:var(--text-on-butter);">🔍</span>
                <input
                    type="text"
                    id="forecastSearchInput"
                    class="search-input"
                    placeholder="Enter city name..."
                    style="color:var(--text-on-butter);"
                    autocomplete="off"
                />
                <div class="unit-toggle">
                    <button class="active" data-unit="metric">°C</button>
                    <button data-unit="imperial">°F</button>
                </div>
                <button id="forecastSearchBtn" class="search-btn">Go</button>
            </div>
        </div>
    </div>

    <!-- Current Weather -->
    <section class="section section-blue" style="padding:3rem 2rem;">
        <div class="container">
            <div class="section-header">
                <div class="section-tag tag-blue">Right Now</div>
                <h2 class="section-title title-blue">Current Conditions</h2>
            </div>
            <div id="weatherDisplay" class="weather-display" style="max-width:900px;margin:0 auto;"></div>
        </div>
    </section>

    <!-- Hourly -->
    <section id="hourlySection" class="section section-butter" style="padding:3rem 2rem;">
        <div class="container">
            <div class="section-header">
                <div class="section-tag tag-butter">Next 24 Hours</div>
                <h2 class="section-title title-butter">Hourly Breakdown</h2>
            </div>
            <div id="hourlyScroll" class="hourly-scroll"></div>
        </div>
    </section>

    <!-- 5-Day -->
    <section id="forecastSection" class="section section-blue" style="padding:3rem 2rem 5rem;">
        <div class="container">
            <div class="section-header">
                <div class="section-tag tag-blue">The Week Ahead</div>
                <h2 class="section-title title-blue">5-Day Forecast</h2>
            </div>
            <div id="forecastGrid" class="forecast-grid"></div>
        </div>
    </section>

    <!-- Wind & Detail Section -->
    <section class="section-split">
        <div class="split-butter">
            <div class="section-tag" style="color:rgba(26,18,0,0.5);font-family:var(--font-mono);font-size:0.7rem;letter-spacing:3px;text-transform:uppercase;margin-bottom:1.5rem;">Explore More</div>
            <h2 class="section-title" style="color:#1a1200;margin-bottom:1.2rem;">Other Cities</h2>
            <p style="color:rgba(26,18,0,0.6);font-size:0.95rem;margin-bottom:1.5rem;line-height:1.7;">
                Quickly jump between global weather destinations.
            </p>
            <div style="display:flex;flex-wrap:wrap;gap:0.6rem;">
                <?php
                $quickCities = ['Islamabad','London','Tokyo','New York','Paris','Dubai','Sydney','Berlin','Mumbai','Cairo'];
                foreach ($quickCities as $qc):
                ?>
                <button onclick="document.getElementById('forecastSearchInput').value='<?= $qc ?>'; searchCityForecast('<?= $qc ?>')"
                    style="
                        background:rgba(26,18,0,0.1);
                        border:1px solid rgba(26,18,0,0.15);
                        color:#1a1200;
                        font-family:var(--font-body);
                        font-size:0.85rem;
                        font-weight:600;
                        padding:6px 14px;
                        border-radius:999px;
                        cursor:pointer;
                        transition:all 0.2s;
                    "
                    onmouseover="this.style.background='rgba(26,18,0,0.2)'"
                    onmouseout="this.style.background='rgba(26,18,0,0.1)'"
                ><?= $qc ?></button>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="split-blue">
            <div class="section-tag" style="color:rgba(243,217,143,0.6);font-family:var(--font-mono);font-size:0.7rem;letter-spacing:3px;text-transform:uppercase;margin-bottom:1.5rem;">Weather Tips</div>
            <h2 class="section-title" style="color:#0e1a2e;margin-bottom:1.5rem;">Reading the Forecast</h2>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <?php
                $tips = [
                    ['💧', 'Humidity', 'Above 70% feels muggy; below 30% feels dry. Ideal comfort sits between 40–60%.'],
                    ['💨', 'Wind Speed', 'Calm is below 5 km/h. Breezy is 15–25 km/h. Strong wind above 50 km/h.'],
                    ['🔵', 'Pressure', 'Rising pressure = fair weather. Falling pressure = storms approaching.'],
                    ['☔', 'Rain Chance', 'Pop (probability of precipitation) above 70% means bring an umbrella.'],
                ];
                foreach ($tips as [$icon, $title, $desc]):
                ?>
                <div style="background:rgba(14,26,46,0.15);border-radius:12px;padding:1rem 1.2rem;border:1px solid rgba(14,26,46,0.2);display:flex;gap:0.8rem;align-items:flex-start;">
                    <span style="font-size:1.4rem;flex-shrink:0;"><?= $icon ?></span>
                    <div>
                        <div style="font-weight:700;color:#0e1a2e;font-size:0.9rem;margin-bottom:2px;"><?= $title ?></div>
                        <div style="color:rgba(14,26,46,0.65);font-size:0.82rem;line-height:1.6;"><?= $desc ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</div>

<?php require 'includes/footer.php'; ?>
