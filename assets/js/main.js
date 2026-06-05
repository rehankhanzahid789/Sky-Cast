/* =========================================
   SKYCAST — main.js
   Vanilla JS: weather, UI, interactions
   ========================================= */

'use strict';

// ── FALLBACK DATA ──────────────────────────
const FALLBACK_CITIES = {
    islamabad: {
        city: 'Islamabad', country: 'PK', countryFull: 'Pakistan',
        temp: 28, feelsLike: 31, tempMin: 22, tempMax: 34,
        condition: 'Partly Cloudy', description: 'partly cloudy skies',
        humidity: 55, windSpeed: 3.8, pressure: 1015,
        visibility: 10, icon: '⛅', iconCode: '02d',
        sunrise: '05:21', sunset: '19:05',
        hourly: [
            { time: 'Now', icon: '⛅', temp: 28, desc: 'Partly Cloudy', pop: 10 },
            { time: '13:00', icon: '☀️', temp: 31, desc: 'Sunny', pop: 5 },
            { time: '14:00', icon: '☀️', temp: 33, desc: 'Sunny', pop: 5 },
            { time: '15:00', icon: '🌤', temp: 34, desc: 'Mostly Sunny', pop: 8 },
            { time: '16:00', icon: '⛅', temp: 32, desc: 'Partly Cloudy', pop: 15 },
            { time: '17:00', icon: '🌥', temp: 30, desc: 'Cloudy', pop: 20 },
            { time: '18:00', icon: '🌥', temp: 27, desc: 'Overcast', pop: 25 },
            { time: '19:00', icon: '🌙', temp: 25, desc: 'Clear Night', pop: 10 },
        ],
        forecast: [
            { day: 'Mon', icon: '☀️', high: 34, low: 21, desc: 'Sunny', humidity: 45 },
            { day: 'Tue', icon: '⛅', high: 32, low: 22, desc: 'Partly Cloudy', humidity: 52 },
            { day: 'Wed', icon: '🌦', high: 29, low: 20, desc: 'Light Showers', humidity: 70 },
            { day: 'Thu', icon: '⛈', high: 26, low: 18, desc: 'Thunderstorm', humidity: 80 },
            { day: 'Fri', icon: '🌤', high: 30, low: 20, desc: 'Mostly Sunny', humidity: 55 },
        ]
    },
    london: {
        city: 'London', country: 'GB', countryFull: 'United Kingdom',
        temp: 14, feelsLike: 12, tempMin: 10, tempMax: 17,
        condition: 'Overcast', description: 'overcast clouds',
        humidity: 78, windSpeed: 5.5, pressure: 1008,
        visibility: 7, icon: '🌥', iconCode: '04d',
        sunrise: '05:45', sunset: '21:10',
        hourly: [
            { time: 'Now', icon: '🌥', temp: 14, desc: 'Overcast', pop: 40 },
            { time: '13:00', icon: '🌦', temp: 15, desc: 'Light Rain', pop: 60 },
            { time: '14:00', icon: '🌧', temp: 14, desc: 'Rain', pop: 75 },
            { time: '15:00', icon: '🌧', temp: 13, desc: 'Rain', pop: 80 },
            { time: '16:00', icon: '🌦', temp: 14, desc: 'Showers', pop: 55 },
            { time: '17:00', icon: '🌥', temp: 13, desc: 'Cloudy', pop: 35 },
            { time: '18:00', icon: '⛅', temp: 12, desc: 'Partly Cloudy', pop: 20 },
            { time: '19:00', icon: '🌙', temp: 11, desc: 'Clear', pop: 10 },
        ],
        forecast: [
            { day: 'Mon', icon: '🌧', high: 15, low: 10, desc: 'Rain', humidity: 80 },
            { day: 'Tue', icon: '⛅', high: 17, low: 11, desc: 'Partly Cloudy', humidity: 68 },
            { day: 'Wed', icon: '☀️', high: 19, low: 12, desc: 'Sunny', humidity: 55 },
            { day: 'Thu', icon: '🌤', high: 18, low: 11, desc: 'Mostly Sunny', humidity: 60 },
            { day: 'Fri', icon: '🌦', high: 16, low: 10, desc: 'Showers', humidity: 72 },
        ]
    },
    tokyo: {
        city: 'Tokyo', country: 'JP', countryFull: 'Japan',
        temp: 22, feelsLike: 23, tempMin: 18, tempMax: 26,
        condition: 'Clear Sky', description: 'clear sky',
        humidity: 62, windSpeed: 4.2, pressure: 1020,
        visibility: 15, icon: '☀️', iconCode: '01d',
        sunrise: '04:30', sunset: '18:55',
        hourly: [
            { time: 'Now', icon: '☀️', temp: 22, desc: 'Clear', pop: 5 },
            { time: '13:00', icon: '☀️', temp: 25, desc: 'Sunny', pop: 5 },
            { time: '14:00', icon: '☀️', temp: 26, desc: 'Sunny', pop: 5 },
            { time: '15:00', icon: '🌤', temp: 26, desc: 'Mostly Sunny', pop: 10 },
            { time: '16:00', icon: '⛅', temp: 25, desc: 'Partly Cloudy', pop: 15 },
            { time: '17:00', icon: '⛅', temp: 23, desc: 'Partly Cloudy', pop: 12 },
            { time: '18:00', icon: '🌇', temp: 21, desc: 'Sunset', pop: 8 },
            { time: '19:00', icon: '🌙', temp: 19, desc: 'Clear Night', pop: 5 },
        ],
        forecast: [
            { day: 'Mon', icon: '☀️', high: 26, low: 17, desc: 'Sunny', humidity: 58 },
            { day: 'Tue', icon: '🌤', high: 25, low: 18, desc: 'Mostly Sunny', humidity: 62 },
            { day: 'Wed', icon: '⛅', high: 24, low: 17, desc: 'Partly Cloudy', humidity: 65 },
            { day: 'Thu', icon: '🌦', high: 22, low: 16, desc: 'Showers', humidity: 78 },
            { day: 'Fri', icon: '☀️', high: 27, low: 18, desc: 'Sunny', humidity: 55 },
        ]
    },
    'new york': {
        city: 'New York', country: 'US', countryFull: 'United States',
        temp: 18, feelsLike: 17, tempMin: 13, tempMax: 22,
        condition: 'Scattered Clouds', description: 'scattered clouds',
        humidity: 65, windSpeed: 7.1, pressure: 1012,
        visibility: 12, icon: '⛅', iconCode: '03d',
        sunrise: '05:30', sunset: '20:15',
        hourly: [
            { time: 'Now', icon: '⛅', temp: 18, desc: 'Partly Cloudy', pop: 20 },
            { time: '13:00', icon: '🌤', temp: 21, desc: 'Mostly Sunny', pop: 15 },
            { time: '14:00', icon: '☀️', temp: 22, desc: 'Sunny', pop: 10 },
            { time: '15:00', icon: '🌤', temp: 22, desc: 'Mostly Sunny', pop: 12 },
            { time: '16:00', icon: '⛅', temp: 20, desc: 'Partly Cloudy', pop: 25 },
            { time: '17:00', icon: '⛅', temp: 18, desc: 'Partly Cloudy', pop: 30 },
            { time: '18:00', icon: '🌥', temp: 16, desc: 'Cloudy', pop: 35 },
            { time: '19:00', icon: '🌙', temp: 14, desc: 'Clear Night', pop: 10 },
        ],
        forecast: [
            { day: 'Mon', icon: '⛅', high: 22, low: 13, desc: 'Scattered Clouds', humidity: 62 },
            { day: 'Tue', icon: '☀️', high: 24, low: 14, desc: 'Sunny', humidity: 55 },
            { day: 'Wed', icon: '🌦', high: 20, low: 12, desc: 'Showers', humidity: 75 },
            { day: 'Thu', icon: '⛈', high: 17, low: 11, desc: 'Thunderstorm', humidity: 82 },
            { day: 'Fri', icon: '🌤', high: 21, low: 13, desc: 'Mostly Sunny', humidity: 58 },
        ]
    }
};

// ── CONDITION → EMOJI MAP ──────────────────
const CONDITION_ICONS = {
    '01d': '☀️', '01n': '🌙',
    '02d': '🌤', '02n': '🌤',
    '03d': '⛅', '03n': '⛅',
    '04d': '🌥', '04n': '🌥',
    '09d': '🌧', '09n': '🌧',
    '10d': '🌦', '10n': '🌦',
    '11d': '⛈', '11n': '⛈',
    '13d': '❄️', '13n': '❄️',
    '50d': '🌫', '50n': '🌫',
};

// ── STATE ──────────────────────────────────
let currentUnit = 'metric'; // metric = Celsius, imperial = Fahrenheit
let currentData = null;
let searchTimeout = null;

// ── DOM HELPERS ────────────────────────────
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

function el(id) { return document.getElementById(id); }

// ── LOADING ────────────────────────────────
function showLoading() {
    const overlay = el('loadingOverlay');
    if (overlay) overlay.classList.add('active');
}

function hideLoading() {
    const overlay = el('loadingOverlay');
    if (overlay) overlay.classList.remove('active');
}

// ── UNIT CONVERSION ────────────────────────
function toF(c) { return Math.round(c * 9/5 + 32); }
function displayTemp(c) {
    if (currentUnit === 'imperial') return `${toF(c)}°F`;
    return `${Math.round(c)}°C`;
}
function displayTempVal(c) {
    return currentUnit === 'imperial' ? toF(c) : Math.round(c);
}
function displayUnit() {
    return currentUnit === 'imperial' ? '°F' : '°C';
}

// ── WEATHER DATA FETCHING ──────────────────
async function fetchWeather(city) {
    showLoading();
    try {
        const resp = await fetch(`api/weather.php?city=${encodeURIComponent(city)}&units=${currentUnit}`);
        const data = await resp.json();
        if (data.error) throw new Error(data.error);
        hideLoading();
        return data;
    } catch (err) {
        hideLoading();
        return null;
    }
}

async function fetchWeatherByCoords(lat, lon) {
    showLoading();
    try {
        const resp = await fetch(`api/weather.php?lat=${lat}&lon=${lon}&units=${currentUnit}`);
        const data = await resp.json();
        if (data.error) throw new Error(data.error);
        hideLoading();
        return data;
    } catch (err) {
        hideLoading();
        return null;
    }
}

// ── FALLBACK LOOKUP ────────────────────────
function getFallback(city) {
    const key = city.toLowerCase().trim();
    // direct match
    if (FALLBACK_CITIES[key]) return FALLBACK_CITIES[key];
    // partial match
    for (const [k, v] of Object.entries(FALLBACK_CITIES)) {
        if (key.includes(k) || k.includes(key)) return v;
    }
    // default fallback
    return FALLBACK_CITIES['islamabad'];
}

// ── FORMAT TIME (UNIX) ─────────────────────
function fmtTime(unix) {
    const d = new Date(unix * 1000);
    return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
}

function fmtHour(unix) {
    const d = new Date(unix * 1000);
    return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
}

function getDayName(unix, short = true) {
    const d = new Date(unix * 1000);
    return d.toLocaleDateString('en-US', { weekday: short ? 'short' : 'long' });
}

function getCurrentDateTime() {
    const now = new Date();
    return now.toLocaleDateString('en-US', {
        weekday: 'long', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
}

// ── RENDER CURRENT WEATHER ─────────────────
function renderWeather(data, isFallback = false) {
    const wd = el('weatherDisplay');
    if (!wd) return;

    currentData = data;

    // For fallback objects vs API objects, normalise
    const city = data.city || data.name || 'Unknown';
    const country = data.country || (data.sys && data.sys.country) || '';
    const temp = data.temp !== undefined ? data.temp : (data.main && data.main.temp);
    const feelsLike = data.feelsLike !== undefined ? data.feelsLike : (data.main && data.main.feels_like);
    const humidity = data.humidity !== undefined ? data.humidity : (data.main && data.main.humidity);
    const pressure = data.pressure !== undefined ? data.pressure : (data.main && data.main.pressure);
    const windSpeed = data.windSpeed !== undefined ? data.windSpeed : (data.wind && data.wind.speed);
    const visibility = data.visibility !== undefined ? data.visibility : ((data.visibility || 0) / 1000);
    const condition = data.condition || (data.weather && data.weather[0] && data.weather[0].main) || 'Clear';
    const description = data.description || (data.weather && data.weather[0] && data.weather[0].description) || '';
    const iconCode = data.iconCode || (data.weather && data.weather[0] && data.weather[0].icon) || '01d';
    const icon = data.icon || CONDITION_ICONS[iconCode] || '🌡️';
    const sunrise = data.sunrise || (data.sys && fmtTime(data.sys.sunrise)) || '06:00';
    const sunset = data.sunset || (data.sys && fmtTime(data.sys.sunset)) || '18:00';

    const tempDisplay = isFallback
        ? (currentUnit === 'imperial' ? toF(temp) : Math.round(temp))
        : Math.round(temp);
    const feelsDisplay = isFallback
        ? (currentUnit === 'imperial' ? toF(feelsLike) : Math.round(feelsLike))
        : Math.round(feelsLike);
    const windDisplay = currentUnit === 'imperial'
        ? (windSpeed * 2.237).toFixed(1) + ' mph'
        : windSpeed.toFixed(1) + ' m/s';

    wd.innerHTML = `
        <div class="weather-main">
            <div>
                <div class="weather-city">${city}</div>
                <div class="weather-country">${country}${isFallback ? ' · Demo Data' : ''}</div>
            </div>
            <div class="weather-icon-wrap">
                <div class="weather-icon-big">${icon}</div>
                <div>
                    <div class="weather-temp">${tempDisplay}<span class="weather-unit">${displayUnit()}</span></div>
                    <div class="weather-condition">${description || condition}</div>
                </div>
            </div>
            <div class="weather-datetime">${getCurrentDateTime()}</div>
            ${isFallback ? '' : `
            <button class="save-city-btn" onclick="saveCity('${city}', '${country}')">
                ⭐ Save City
            </button>`}
        </div>
        <div class="weather-aside">
            <div class="aside-label">Details</div>
            <div class="weather-stats-grid">
                <div class="stat-box">
                    <div class="stat-icon">🌡️</div>
                    <div class="stat-label">Feels Like</div>
                    <div class="stat-value">${feelsDisplay}${displayUnit()}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon">💧</div>
                    <div class="stat-label">Humidity</div>
                    <div class="stat-value">${humidity}%</div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon">💨</div>
                    <div class="stat-label">Wind</div>
                    <div class="stat-value">${windDisplay}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon">🔵</div>
                    <div class="stat-label">Pressure</div>
                    <div class="stat-value">${pressure} hPa</div>
                </div>
            </div>
            <div class="sun-times">
                <div class="sun-item">
                    <div class="sun-icon">🌅</div>
                    <div class="sun-label">Sunrise</div>
                    <div class="sun-time">${sunrise}</div>
                </div>
                <div class="sun-item">
                    <div class="sun-icon">🌇</div>
                    <div class="sun-label">Sunset</div>
                    <div class="sun-time">${sunset}</div>
                </div>
            </div>
        </div>
    `;

    wd.classList.remove('hidden');
    wd.style.animation = 'none';
    requestAnimationFrame(() => { wd.style.animation = 'slideUp 0.6s ease forwards'; });
}

// ── RENDER HOURLY FORECAST ─────────────────
function renderHourly(hourlyData, isFallback = false) {
    const container = el('hourlyScroll');
    if (!container) return;

    let html = '';
    const items = isFallback ? hourlyData : hourlyData.slice(0, 12);

    items.forEach((h, i) => {
        const time = isFallback ? h.time : fmtHour(h.dt);
        const icon = isFallback ? h.icon : (CONDITION_ICONS[h.weather[0].icon] || '⛅');
        const temp = isFallback
            ? (currentUnit === 'imperial' ? toF(h.temp) : Math.round(h.temp))
            : Math.round(h.main ? h.main.temp : h.temp);
        const desc = isFallback ? h.desc : (h.weather[0].description || '');
        const pop = isFallback ? h.pop : Math.round((h.pop || 0) * 100);

        html += `
            <div class="hour-card ${i === 0 ? 'active' : ''}">
                <div class="hour-time">${i === 0 ? 'Now' : time}</div>
                <div class="hour-icon">${icon}</div>
                <div class="hour-temp">${temp}${displayUnit()}</div>
                <div class="hour-desc">${desc}</div>
                ${pop > 0 ? `<div class="hour-pop">💧 ${pop}%</div>` : ''}
            </div>
        `;
    });

    container.innerHTML = html;
    const hourlySection = el('hourlySection');
    if (hourlySection) hourlySection.classList.remove('hidden');
}

// ── RENDER 5-DAY FORECAST ──────────────────
function renderForecast(forecastData, isFallback = false) {
    const container = el('forecastGrid');
    if (!container) return;

    let html = '';

    if (isFallback) {
        forecastData.forEach(d => {
            const high = currentUnit === 'imperial' ? toF(d.high) : d.high;
            const low = currentUnit === 'imperial' ? toF(d.low) : d.low;
            html += `
                <div class="day-card">
                    <div class="day-name">${d.day}</div>
                    <div class="day-icon">${d.icon}</div>
                    <div class="day-temp-high">${high}${displayUnit()}</div>
                    <div class="day-temp-low">Low ${low}${displayUnit()}</div>
                    <div class="day-desc">${d.desc}</div>
                    <div class="day-humidity">💧 ${d.humidity}%</div>
                </div>
            `;
        });
    } else {
        // API data — group by day
        const daily = {};
        forecastData.forEach(item => {
            const day = getDayName(item.dt, true);
            if (!daily[day]) {
                daily[day] = { temps: [], icons: [], descs: [], humidities: [], pops: [] };
            }
            daily[day].temps.push(item.main.temp);
            daily[day].icons.push(item.weather[0].icon);
            daily[day].descs.push(item.weather[0].description);
            daily[day].humidities.push(item.main.humidity);
            daily[day].pops.push(item.pop || 0);
        });

        Object.entries(daily).slice(0, 5).forEach(([day, d]) => {
            const high = Math.round(Math.max(...d.temps));
            const low = Math.round(Math.min(...d.temps));
            const iconCode = d.icons[Math.floor(d.icons.length / 2)] || '01d';
            const icon = CONDITION_ICONS[iconCode] || '⛅';
            const desc = d.descs[Math.floor(d.descs.length / 2)];
            const humidity = Math.round(d.humidities.reduce((a,b) => a+b,0) / d.humidities.length);
            html += `
                <div class="day-card">
                    <div class="day-name">${day}</div>
                    <div class="day-icon">${icon}</div>
                    <div class="day-temp-high">${high}${displayUnit()}</div>
                    <div class="day-temp-low">Low ${low}${displayUnit()}</div>
                    <div class="day-desc">${desc}</div>
                    <div class="day-humidity">💧 ${humidity}%</div>
                </div>
            `;
        });
    }

    container.innerHTML = html;
    const forecastSection = el('forecastSection');
    if (forecastSection) forecastSection.classList.remove('hidden');
}

// ── MAIN SEARCH FUNCTION ───────────────────
async function searchCity(city) {
    if (!city || !city.trim()) return;
    city = city.trim();

    // Try API first
    const data = await fetchWeather(city);

    if (data && data.current) {
        // Successful API response
        renderWeather(data.current, false);
        if (data.hourly) renderHourly(data.hourly, false);
        if (data.forecast) renderForecast(data.forecast, false);
        logHistory(city, data.current);
    } else {
        // Use fallback
        const fallback = getFallback(city);
        renderWeather(fallback, true);
        renderHourly(fallback.hourly, true);
        renderForecast(fallback.forecast, true);
        showAlert('Using demo data. Enter your OpenWeather API key in .env for live data.', 'info');
    }

    // Scroll to results
    const wd = el('weatherDisplay');
    if (wd) {
        setTimeout(() => wd.scrollIntoView({ behavior: 'smooth', block: 'center' }), 200);
    }
}

// ── GEOLOCATION ────────────────────────────
function getMyLocation() {
    if (!navigator.geolocation) {
        showAlert('Geolocation is not supported by your browser.', 'error');
        return;
    }

    showLoading();
    navigator.geolocation.getCurrentPosition(
        async (pos) => {
            const { latitude: lat, longitude: lon } = pos.coords;
            const data = await fetchWeatherByCoords(lat, lon);

            if (data && data.current) {
                renderWeather(data.current, false);
                if (data.hourly) renderHourly(data.hourly, false);
                if (data.forecast) renderForecast(data.forecast, false);
            } else {
                // Approximate fallback using Islamabad coords area
                const fallback = FALLBACK_CITIES['islamabad'];
                fallback.city = 'Your Location';
                renderWeather(fallback, true);
                renderHourly(fallback.hourly, true);
                renderForecast(fallback.forecast, true);
                showAlert('Using demo data for your location.', 'info');
            }
            hideLoading();
        },
        (err) => {
            hideLoading();
            showAlert('Unable to get your location. Please search manually.', 'error');
        },
        { timeout: 10000 }
    );
}

// ── UNIT TOGGLE ────────────────────────────
function setUnit(unit) {
    currentUnit = unit;
    const btns = $$('.unit-toggle button');
    btns.forEach(b => b.classList.toggle('active', b.dataset.unit === unit));

    // Re-render if we have data
    if (currentData) {
        const city = currentData.city || currentData.name || 'islamabad';
        searchCity(city);
    }
}

// ── SAVE CITY ──────────────────────────────
async function saveCity(city, country) {
    try {
        const resp = await fetch('api/save_city.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ city, country })
        });
        const data = await resp.json();
        if (data.success) {
            showAlert(`✅ ${city} saved to favorites!`, 'success');
            const btn = $('.save-city-btn');
            if (btn) { btn.textContent = '⭐ Saved!'; btn.classList.add('saved'); }
        } else {
            showAlert(data.message || 'Login to save cities.', 'info');
        }
    } catch {
        showAlert('Login to save cities to your favorites.', 'info');
    }
}

// ── REMOVE SAVED CITY ──────────────────────
async function removeCity(id, el_) {
    try {
        const resp = await fetch('api/save_city.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const data = await resp.json();
        if (data.success && el_) el_.closest('.city-chip').remove();
    } catch {}
}

// ── LOG SEARCH HISTORY ─────────────────────
function logHistory(city, weatherData) {
    try {
        fetch('api/history.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                city,
                temperature: weatherData.temp || (weatherData.main && weatherData.main.temp),
                condition: weatherData.condition || (weatherData.weather && weatherData.weather[0] && weatherData.weather[0].main),
                humidity: weatherData.humidity || (weatherData.main && weatherData.main.humidity),
                wind_speed: weatherData.windSpeed || (weatherData.wind && weatherData.wind.speed)
            })
        });
    } catch {}
}

// ── ALERT SYSTEM ──────────────────────────
function showAlert(msg, type = 'info') {
    // Remove existing alerts
    $$('.floating-alert').forEach(a => a.remove());

    const alert = document.createElement('div');
    alert.className = `floating-alert alert alert-${type}`;
    alert.style.cssText = `
        position: fixed;
        top: 90px;
        right: 20px;
        z-index: 9998;
        max-width: 360px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease forwards;
    `;
    alert.textContent = msg;
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 4000);
}

// ── SEARCH AUTOCOMPLETE ────────────────────
const POPULAR_CITIES = [
    'Islamabad', 'Karachi', 'Lahore', 'London', 'Tokyo',
    'New York', 'Paris', 'Dubai', 'Singapore', 'Sydney',
    'Berlin', 'Toronto', 'Mumbai', 'Cairo', 'Istanbul'
];

function initSearch() {
    const input = el('searchInput');
    const btn = el('searchBtn');
    const suggestions = el('searchSuggestions');

    if (!input) return;

    input.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        const val = input.value.trim();
        if (!val || val.length < 2) {
            suggestions && suggestions.classList.remove('visible');
            return;
        }

        searchTimeout = setTimeout(() => {
            const matches = POPULAR_CITIES.filter(c =>
                c.toLowerCase().startsWith(val.toLowerCase())
            ).slice(0, 5);

            if (matches.length && suggestions) {
                suggestions.innerHTML = matches.map(c => `
                    <div class="suggestion-item" onclick="selectCity('${c}')">
                        <span class="sugg-icon">📍</span> ${c}
                    </div>
                `).join('');
                suggestions.classList.add('visible');
            } else {
                suggestions && suggestions.classList.remove('visible');
            }
        }, 200);
    });

    input.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            suggestions && suggestions.classList.remove('visible');
            searchCity(input.value);
        }
    });

    if (btn) btn.addEventListener('click', () => {
        suggestions && suggestions.classList.remove('visible');
        searchCity(input.value);
    });

    // Close suggestions on outside click
    document.addEventListener('click', e => {
        if (!e.target.closest('.search-container')) {
            suggestions && suggestions.classList.remove('visible');
        }
    });
}

function selectCity(city) {
    const input = el('searchInput');
    const suggestions = el('searchSuggestions');
    if (input) input.value = city;
    if (suggestions) suggestions.classList.remove('visible');
    searchCity(city);
}

// ── MOBILE NAV ─────────────────────────────
function toggleMobileNav() {
    const nav = el('mobileNav');
    if (nav) nav.classList.toggle('open');
}

// ── UNIT TOGGLE INIT ───────────────────────
function initUnitToggle() {
    $$('.unit-toggle button').forEach(btn => {
        btn.addEventListener('click', () => setUnit(btn.dataset.unit));
    });
}

// ── WIND COMPASS ───────────────────────────
function setWindDeg(deg) {
    const needle = $('.compass-needle');
    if (needle) needle.style.transform = `translateX(-50%) rotate(${deg}deg)`;
}

// ── FORECAST PAGE INIT ─────────────────────
function initForecastPage() {
    const urlParams = new URLSearchParams(window.location.search);
    const city = urlParams.get('city') || 'Islamabad';
    const input = el('forecastSearchInput');
    const btn = el('forecastSearchBtn');

    if (input) input.value = city;
    searchCityForecast(city);

    if (btn) btn.addEventListener('click', () => searchCityForecast(input.value));
    if (input) input.addEventListener('keydown', e => {
        if (e.key === 'Enter') searchCityForecast(input.value);
    });
}

async function searchCityForecast(city) {
    const data = await fetchWeather(city);

    if (data && data.current) {
        renderWeather(data.current, false);
        if (data.hourly) renderHourly(data.hourly, false);
        if (data.forecast) renderForecast(data.forecast, false);
    } else {
        const fallback = getFallback(city);
        renderWeather(fallback, true);
        renderHourly(fallback.hourly, true);
        renderForecast(fallback.forecast, true);
    }
}

// ── DASHBOARD CITY CLICK ───────────────────
function cityClick(city) {
    window.location.href = `forecast.php?city=${encodeURIComponent(city)}`;
}

// ── INIT ───────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    initSearch();
    initUnitToggle();

    const page = document.body.dataset.page;
    if (page === 'forecast') initForecastPage();
    if (page === 'index') {
        // Auto-load default city on home
        const fallback = FALLBACK_CITIES['islamabad'];
        renderWeather(fallback, true);
        renderHourly(fallback.hourly, true);
        renderForecast(fallback.forecast, true);
    }
});
