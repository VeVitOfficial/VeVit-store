<?php
// PHP built-in dev server router — not used in production (Apache handles routing)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Block access to internal files and sensitive extensions
if (preg_match('#^/(?:lib|migrations|tests|docs|\.github|\.claude|config(?:_secret)?\.php|\.env)#i', $uri)
    || preg_match('#\.(?:sql|md|json|ya?ml|lock|sh|env)$#i', $uri)) {
    http_response_code(403);
    exit('Forbidden');
}

// Serve existing static files directly
$file = __DIR__ . $uri;
if ($uri !== '/' && is_file($file)) {
    return false; // serve as-is
}

// Route / to index.php
if ($uri === '/' || $uri === '/index.php') {
    require __DIR__ . '/index.php';
    exit;
}

// Route known PHP pages
$phpFile = __DIR__ . preg_replace('#\?.*$#', '', $uri);
if (str_ends_with($phpFile, '.php') && is_file($phpFile)) {
    require $phpFile;
    exit;
}

// 404
http_response_code(404);
require_once __DIR__ . '/404.php';
