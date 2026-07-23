<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Aktuální přihlášený uživatel pro hydrataci navigace ve statickém index.html.
$user = getCurrentUser();

echo json_encode([
    'user' => $user ? [
        'id' => $user['id'],
        'email' => $user['email'],
        'full_name' => $user['full_name'],
        'nickname' => $user['nickname'],
        'avatar_url' => $user['avatar_url'],
        'tier' => $user['tier'],
    ] : null,
]);