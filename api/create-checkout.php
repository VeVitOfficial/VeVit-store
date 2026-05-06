<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/stripe-php/init.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || empty($input['items'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing items']);
    exit;
}

$user = getCurrentUser();
$userId = $user ? $user['id'] : null;

// Rate limit: max 5 requests per minute per IP
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateKey = sys_get_temp_dir() . '/vevit_checkout_' . md5($ip) . '.txt';
$rateData = @file_get_contents($rateKey);
$rateWindow = [];
$now = time();
if ($rateData) {
    $rateWindow = array_filter(explode(',', $rateData), fn($t) => $now - (int)$t < 60);
}
if (count($rateWindow) >= 5) {
    http_response_code(429);
    echo json_encode(['error' => 'Příliš mnoho pokusů. Zkuste to za chvíli.']);
    exit;
}
$rateWindow[] = $now;
file_put_contents($rateKey, implode(',', $rateWindow), LOCK_EX);

$items = $input['items'];
$email = filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL);
$name = trim($input['name'] ?? '');
$shipping = $input['shipping'] ?? null;
$notes = trim($input['notes'] ?? '');

Stripe::setApiKey(STRIPE_SECRET_KEY);

$lineItems = [];
$total = 0;
$hasPhysical = false;

foreach ($items as $item) {
    $productId = (int) ($item['product_id'] ?? 0);
    $qty = max(1, (int) ($item['quantity'] ?? 1));
    $stmt = $pdo->prepare('SELECT * FROM store_products WHERE id = ? AND is_active = TRUE');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    if (!$product) continue;

    $price = ($product['sale_price'] && floatval($product['sale_price']) > 0) ? floatval($product['sale_price']) : floatval($product['price']);
    $total += $price * $qty;
    if ($product['type'] === 'physical') $hasPhysical = true;

    $lineItems[] = [
        'price_data[currency]' => 'czk',
        'price_data[product_data][name]' => $product['name'],
        'price_data[unit_amount]' => (int) ($price * 100),
        'quantity' => $qty,
    ];
}

if (empty($lineItems)) {
    http_response_code(400);
    echo json_encode(['error' => 'No valid products']);
    exit;
}

// Shipping cost
$shippingCost = $total >= 1000 ? 0 : 99;
if ($hasPhysical && $shippingCost > 0) {
    $lineItems[] = [
        'price_data[currency]' => 'czk',
        'price_data[product_data][name]' => 'Doprava',
        'price_data[unit_amount]' => $shippingCost * 100,
        'quantity' => 1,
    ];
    $total += $shippingCost;
}

// Generate order number
$year = date('Y');
$countStmt = $pdo->query("SELECT COUNT(*) FROM store_orders WHERE created_at >= NOW() - INTERVAL '1 year'");
$count = (int) $countStmt->fetchColumn() + 1;
$orderNumber = sprintf('VVS-%s-%05d', $year, $count);

// Create order record
$shippingJson = $shipping && $hasPhysical ? json_encode($shipping, JSON_UNESCAPED_UNICODE) : null;
$insert = $pdo->prepare("INSERT INTO store_orders (order_number, user_id, status, total, currency, customer_email, customer_name, shipping_address, notes, created_at, updated_at) VALUES (?, ?, 'pending', ?, 'czk', ?, ?, ?, ?, NOW(), NOW())");
$insert->execute([$orderNumber, $userId, $total, $email, $name, $shippingJson, $notes]);
$orderId = $pdo->lastInsertId('store_orders_id_seq');

// Insert order items
foreach ($items as $item) {
    $productId = (int) ($item['product_id'] ?? 0);
    $qty = max(1, (int) ($item['quantity'] ?? 1));
    $stmt = $pdo->prepare('SELECT * FROM store_products WHERE id = ?');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    if (!$product) continue;

    $price = ($product['sale_price'] && floatval($product['sale_price']) > 0) ? floatval($product['sale_price']) : floatval($product['price']);
    $token = $product['type'] === 'digital' ? bin2hex(random_bytes(32)) : null;
    $expires = $token ? date('Y-m-d H:i:s', strtotime('+72 hours')) : null;

    $pdo->prepare("INSERT INTO store_order_items (order_id, product_id, product_name, product_type, quantity, unit_price, download_token, download_expires_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
        ->execute([$orderId, $productId, $product['name'], $product['type'], $qty, $price, $token, $expires]);
}

// Build Stripe checkout params
$stripeParams = [
    'mode' => 'payment',
    'success_url' => 'https://' . ($_SERVER['HTTP_HOST'] ?? 'vevit.store') . '/success.php?order=' . urlencode($orderNumber),
    'cancel_url' => 'https://' . ($_SERVER['HTTP_HOST'] ?? 'vevit.store') . '/cancel.php',
    'client_reference_id' => $orderNumber,
    'customer_email' => $email,
];

foreach ($lineItems as $i => $li) {
    foreach ($li as $k => $v) {
        $stripeParams["line_items[$i][$k]"] = $v;
    }
}

if ($hasPhysical) {
    $stripeParams['shipping_address_collection[allowed_countries][]'] = 'CZ';
    $stripeParams['shipping_address_collection[allowed_countries][]'] = 'SK';
}

$res = Stripe::request('POST', 'checkout/sessions', $stripeParams);

if ($res['code'] !== 200 || empty($res['body']['url'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Stripe error: ' . ($res['body']['error']['message'] ?? 'Unknown')]);
    exit;
}

$sessionId = $res['body']['id'] ?? '';
$pdo->prepare("UPDATE store_orders SET stripe_session_id = ? WHERE id = ?")
    ->execute([$sessionId, $orderId]);

echo json_encode(['url' => $res['body']['url'], 'order_number' => $orderNumber]);
