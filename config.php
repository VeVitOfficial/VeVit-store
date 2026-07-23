<?php
// config.php — Database connection (PostgreSQL / Supabase)

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/config_secret.php';

try {
    $pdo = new PDO('pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    die('Database connection failed: ' . $e->getMessage());
}

function h(?string $text): string {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/lib/auth.php';
