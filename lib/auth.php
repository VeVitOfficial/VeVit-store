<?php
// VeVit Store — user authentication against main Vevit database (public.users)

if (session_status() !== PHP_SESSION_ACTIVE) {
    if (function_exists('store_start_session') && isset($GLOBALS['storeConfig']['session'])) {
        store_start_session($GLOBALS['storeConfig']['session']);
    } else {
        session_start();
    }
}

function getCurrentUser(): ?array {
    global $pdo;
    if (empty($_SESSION['user_id'])) return null;
    $stmt = $pdo->prepare('SELECT id, email, full_name, nickname, avatar_url, tier FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function loginUser(string $email, string $password): ?array {
    global $pdo;
    $stmt = $pdo->prepare('SELECT id, email, full_name, nickname, avatar_url, tier, password FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user) return null;
    if (!password_verify($password, $user['password'])) return null;
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    unset($user['password']);
    return $user;
}

function logoutUser(): void {
    if (function_exists('store_destroy_session')) {
        store_destroy_session();
        return;
    }

    $_SESSION = [];
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', ['expires' => time() - 3600, 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
    }
    session_destroy();
}

function requireLogin(): array {
    $user = getCurrentUser();
    if (!$user) {
        header('Location: index.html?login=1');
        exit;
    }
    return $user;
}
