<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$category = $_GET['category'] ?? '';
$type = $_GET['type'] ?? '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'featured';
$maxPrice = isset($_GET['max_price']) ? (float) $_GET['max_price'] : 10000;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$perPage = 12;

$where = ['p.is_active = TRUE'];
$params = [];

if ($category) {
    $slugs = array_map('trim', explode(',', $category));
    $placeholders = implode(',', array_fill(0, count($slugs), '?'));
    $where[] = "c.slug IN ($placeholders)";
    $params = array_merge($params, $slugs);
}
if ($type && in_array($type, ['physical', 'digital'])) {
    $where[] = 'p.type = ?';
    $params[] = $type;
}
if ($search) {
    $where[] = '(p.name ILIKE ? OR p.short_desc ILIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}
$where[] = 'COALESCE(p.sale_price, p.price) <= ?';
$params[] = $maxPrice;

$order = match ($sort) {
    'newest' => 'p.created_at DESC',
    'cheapest' => 'COALESCE(p.sale_price, p.price) ASC',
    'expensive' => 'COALESCE(p.sale_price, p.price) DESC',
    default => 'p.featured DESC, p.created_at DESC'
};

$whereSql = implode(' AND ', $where);

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM store_products p LEFT JOIN store_categories c ON p.category_id = c.id WHERE $whereSql");
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($page, $totalPages);

$offset = ($page - 1) * $perPage;
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM store_products p LEFT JOIN store_categories c ON p.category_id = c.id WHERE $whereSql ORDER BY $order LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$products = $stmt->fetchAll();

echo json_encode([
    'products' => $products,
    'page' => $page,
    'total_pages' => $totalPages,
    'total' => $total
]);
