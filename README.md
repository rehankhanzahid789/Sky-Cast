# SkyCast

Premium weather web app — pure PHP + MySQL + vanilla JS + CSS.

## Setup
1. Copy this folder into your web root (XAMPP `htdocs/`, MAMP, etc.).
2. Create the database: import `skycast.sql` in phpMyAdmin (or `mysql < skycast.sql`).
3. Open `.env` and set:
   - `OPENWEATHER_API_KEY` — get a free key at https://openweathermap.org/api
   - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` for your MySQL
4. Visit `http://localhost/skycast/` in your browser.

## Features
- Hero with live current weather, search & geolocation
- Hourly + 5-day forecast (OpenWeather free tier)
- Sunrise/sunset arc, daylight progress
- Temperature trend chart (canvas, no deps)
- Glassmorphism UI, dual-color design (#7298C7 sky / #F3D98F butter)
- Dynamic theme (sunny / cloudy / rain / night)
- Auth (register, login, logout, sessions)
- Saved cities per user (MySQL)
- Fully responsive

## Security
- Prepared statements (PDO) everywhere
- Server-side input validation
- API key stays server-side (proxied via `api/weather.php`)
- Passwords hashed with `password_hash` (bcrypt)

All paths are relative.
