<?php
header('Content-Type: application/json');
session_start();

require '../includes/db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    if (!DB_AVAILABLE) { echo json_encode(['success' => false]); exit; }

    $body  = json_decode(file_get_contents('php://input'), true);
    $city  = isset($body['city']) ? trim(strip_tags($body['city'])) : null;
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

    if (!$city) { echo json_encode(['success' => false]); exit; }

    try {
        global $pdo;
        $stmt = $pdo->prepare('
            INSERT INTO weather_history (user_id, city_name, temperature, condition_text, humidity, wind_speed)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $userId,
            $city,
            $body['temperature'] ?? null,
            $body['condition']   ?? null,
            $body['humidity']    ?? null,
            $body['wind_speed']  ?? null,
        ]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false]);
    }

} elseif ($method === 'GET') {
    if (!isset($_SESSION['user_id']) || !DB_AVAILABLE) {
        echo json_encode(['history' => []]);
        exit;
    }

    $userId = (int)$_SESSION['user_id'];
    try {
        global $pdo;
        $stmt = $pdo->prepare('
            SELECT city_name, temperature, condition_text, humidity, wind_speed, searched_at
            FROM weather_history
            WHERE user_id = ?
            ORDER BY searched_at DESC
            LIMIT 20
        ');
        $stmt->execute([$userId]);
        echo json_encode(['history' => $stmt->fetchAll()]);
    } catch (PDOException $e) {
        echo json_encode(['history' => []]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false]);
}
