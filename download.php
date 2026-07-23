<?php
require_once __DIR__ . '/config.php';

$token = $_GET['token'] ?? '';
if (!$token) { http_response_code(400); die('Chybí token'); }

$stmt = $pdo->prepare("
    SELECT i.*, p.download_file, o.status
    FROM store_order_items i
    JOIN store_products p ON i.product_id = p.id
    JOIN store_orders o ON i.order_id = o.id
    WHERE i.download_token = ? AND i.product_type = 'digital'
");
$stmt->execute([$token]);
$item = $stmt->fetch();

if (!$item) { http_response_code(404); die('Neplatný odkaz ke stažení.'); }
if ($item['status'] !== 'paid') { http_response_code(403); die('Objednávka není zaplacena.'); }
if ($item['download_expires_at'] && strtotime($item['download_expires_at']) < time()) {
    http_response_code(410); die('Odkaz ke stažení vypršel.');
}
if ($item['download_count'] >= 5) {
    http_response_code(410); die('Maximální počet stažení byl dosažen.');
}

$filePath = __DIR__ . '/' . $item['download_file'];
if (!$item['download_file'] || !file_exists($filePath)) {
    http_response_code(404); die('Soubor nebyl nalezen.');
}

// Increment counter
$pdo->prepare("UPDATE store_order_items SET download_count = download_count + 1 WHERE id = ?")
   ->execute([$item['id']]);

$filename = basename($filePath);
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($filePath) ?: 'application/octet-stream';

header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;
