<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/stripe-php/init.php';

header('Content-Type: application/json');

Stripe::setApiKey(STRIPE_SECRET_KEY);

$payload = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

$event = null;
try {
    // Simple signature verification
    if (STRIPE_WEBHOOK_SECRET && $sigHeader) {
        $timestamp = null;
        $signatures = [];
        foreach (explode(',', $sigHeader) as $part) {
            $kv = explode('=', trim($part), 2);
            if (count($kv) === 2) {
                if ($kv[0] === 't') $timestamp = $kv[1];
                if ($kv[0] === 'v1') $signatures[] = $kv[1];
            }
        }
        $signedPayload = $timestamp . '.' . $payload;
        $expected = hash_hmac('sha256', $signedPayload, STRIPE_WEBHOOK_SECRET);
        if (!in_array($expected, $signatures)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid signature']);
            exit;
        }
    }
    $event = json_decode($payload, true);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

if (!$event || empty($event['type'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No event type']);
    exit;
}

$type = $event['type'];
$object = $event['data']['object'] ?? [];

if ($type === 'checkout.session.completed') {
    $orderNumber = $object['client_reference_id'] ?? '';
    $sessionId = $object['id'] ?? '';
    $paymentIntent = $object['payment_intent'] ?? '';

    if ($orderNumber) {
        $pdo->prepare("UPDATE store_orders SET status = 'paid', stripe_payment_intent = ? WHERE order_number = ? AND stripe_session_id = ?")
            ->execute([$paymentIntent, $orderNumber, $sessionId]);

        // Decrease physical stock
        $stmt = $pdo->prepare("SELECT product_id, quantity FROM store_order_items WHERE order_id = (SELECT id FROM store_orders WHERE order_number = ?)");
        $stmt->execute([$orderNumber]);
        foreach ($stmt->fetchAll() as $row) {
            $pdo->prepare("UPDATE store_products SET stock = GREATEST(0, stock - ?) WHERE id = ? AND type = 'physical' AND stock IS NOT NULL")
                ->execute([$row['quantity'], $row['product_id']]);
        }
    }
}

if ($type === 'charge.refunded') {
    $paymentIntent = $object['payment_intent'] ?? '';
    if ($paymentIntent) {
        $pdo->prepare("UPDATE store_orders SET status = 'refunded' WHERE stripe_payment_intent = ?")
            ->execute([$paymentIntent]);
    }
}

http_response_code(200);
echo json_encode(['received' => true]);
