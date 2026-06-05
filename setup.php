<?php
require 'includes/db.php';

if (!DB_AVAILABLE) {
    die("Database not available. Please configure your .env file.");
}

$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS saved_cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    city_name VARCHAR(100) NOT NULL,
    country_code VARCHAR(5),
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_city (user_id, city_name)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS weather_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    city_name VARCHAR(100) NOT NULL,
    temperature DECIMAL(5,2),
    condition_text VARCHAR(100),
    humidity INT,
    wind_speed DECIMAL(6,2),
    searched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_searched_at (searched_at)
) ENGINE=InnoDB;
";

try {
    $pdo->exec($sql);
    echo "<pre>✅ Database tables created successfully!</pre>";
} catch (PDOException $e) {
    echo "<pre>❌ Error: " . htmlspecialchars($e->getMessage()) . "</pre>";
}
