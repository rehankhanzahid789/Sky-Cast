<?php
header('Content-Type: application/json');
session_start();

require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please login to save cities.']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];
$body   = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    $city    = isset($body['city'])    ? trim(strip_tags($body['city']))    : null;
    $country = isset($body['country']) ? trim(strip_tags($body['country'])) : null;

    if (!$city) {
        echo json_encode(['success' => false, 'message' => 'City name required.']);
        exit;
    }

    if (!DB_AVAILABLE) {
        echo json_encode(['success' => false, 'message' => 'Database not available.']);
        exit;
    }

    try {
        global $pdo;
        $stmt = $pdo->prepare('INSERT IGNORE INTO saved_cities (user_id, city_name, country_code) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $city, $country]);
        echo json_encode(['success' => true, 'message' => 'City saved!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Could not save city.']);
    }

} elseif ($method === 'DELETE') {
    $id = isset($body['id']) ? (int)$body['id'] : 0;

    if (!$id || !DB_AVAILABLE) {
        echo json_encode(['success' => false]);
        exit;
    }

    try {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM saved_cities WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}
