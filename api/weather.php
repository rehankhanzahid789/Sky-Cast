<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require '../includes/db.php';

$apiKey = API_KEY;

// Sanitise inputs
$city = isset($_GET['city']) ? trim(strip_tags($_GET['city'])) : null;
$lat  = isset($_GET['lat'])  ? floatval($_GET['lat'])  : null;
$lon  = isset($_GET['lon'])  ? floatval($_GET['lon'])  : null;
$units = (isset($_GET['units']) && $_GET['units'] === 'imperial') ? 'imperial' : 'metric';

// If no API key, return demo error so JS uses fallback
if (!$apiKey || $apiKey === 'your_openweather_api_key_here') {
    http_response_code(503);
    echo json_encode(['error' => 'API key not configured. Using fallback data.']);
    exit;
}

// Build URLs
if ($lat !== null && $lon !== null) {
    $currentUrl  = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units={$units}&appid={$apiKey}";
    $forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&units={$units}&appid={$apiKey}";
} elseif ($city) {
    $cityEnc = urlencode($city);
    $currentUrl  = "https://api.openweathermap.org/data/2.5/weather?q={$cityEnc}&units={$units}&appid={$apiKey}";
    $forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?q={$cityEnc}&units={$units}&appid={$apiKey}";
} else {
    http_response_code(400);
    echo json_encode(['error' => 'City or coordinates required.']);
    exit;
}

// Fetch with cURL
function fetchUrl($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT      => 'SkyCast/1.0',
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$body, $code];
}

[$currentBody, $currentCode] = fetchUrl($currentUrl);
[$forecastBody, $forecastCode] = fetchUrl($forecastUrl);

if ($currentCode !== 200) {
    http_response_code($currentCode);
    echo json_encode(['error' => 'City not found or API error.', 'code' => $currentCode]);
    exit;
}

$current  = json_decode($currentBody, true);
$forecast = json_decode($forecastBody, true);

// Normalize current weather
$result = [
    'current' => [
        'city'       => $current['name'],
        'country'    => $current['sys']['country'] ?? '',
        'temp'       => $current['main']['temp'],
        'feelsLike'  => $current['main']['feels_like'],
        'tempMin'    => $current['main']['temp_min'],
        'tempMax'    => $current['main']['temp_max'],
        'humidity'   => $current['main']['humidity'],
        'pressure'   => $current['main']['pressure'],
        'windSpeed'  => $current['wind']['speed'],
        'windDeg'    => $current['wind']['deg'] ?? 0,
        'visibility' => isset($current['visibility']) ? round($current['visibility'] / 1000, 1) : null,
        'condition'  => $current['weather'][0]['main'] ?? '',
        'description'=> $current['weather'][0]['description'] ?? '',
        'iconCode'   => $current['weather'][0]['icon'] ?? '01d',
        'sunrise'    => date('H:i', $current['sys']['sunrise']),
        'sunset'     => date('H:i', $current['sys']['sunset']),
        'dt'         => $current['dt'],
        'timezone'   => $current['timezone'],
    ],
    'hourly'   => [],
    'forecast' => [],
];

// Hourly (next 24h from 3-hour list)
if ($forecast && isset($forecast['list'])) {
    $result['hourly']   = array_slice($forecast['list'], 0, 8);
    $result['forecast'] = $forecast['list'];
}

echo json_encode($result);
