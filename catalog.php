<?php
require_once __DIR__ . '/config.php';

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 12;
$search = trim((string)($_GET['search'] ?? ''));
$categoryFilter = trim((string)($_GET['category'] ?? ''));
$typeFilter = $_GET['type'] ?? '';
$sort = $_GET['sort'] ?? 'featured';
$dealsOnly = !empty($_GET['deals']);
$brandFilter = trim((string)($_GET['brand'] ?? ''));
$maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 0;

$where = ['p.is_active = TRUE'];
$params = [];

if ($categoryFilter) {
    $where[] = 'c.slug = ?';
    $params[] = $categoryFilter;
}
if ($typeFilter && in_array($typeFilter, ['physical','digital'])) {
    $where[] = 'p.type = ?';
    $params[] = $typeFilter;
}
if ($brandFilter) {
    $where[] = 'p.brand = ?';
    $params[] = $brandFilter;
}
if ($search) {
    $where[] = '(p.name ILIKE ? OR p.short_desc ILIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}
if ($dealsOnly) {
    $where[] = 'p.sale_price IS NOT NULL AND p.sale_price > 0';
}
if ($maxPrice > 0) {
    $where[] = 'COALESCE(p.sale_price, p.price) <= ?';
    $params[] = $maxPrice;
}

$order = match($sort) {
    'newest' => 'p.created_at DESC',
    'cheapest' => 'COALESCE(p.sale_price, p.price) ASC',
    'expensive' => 'COALESCE(p.sale_price, p.price) DESC',
    'bestselling' => '(SELECT COALESCE(SUM(oi.quantity), 0) FROM store_order_items oi JOIN store_orders o ON oi.order_id = o.id WHERE oi.product_id = p.id AND o.status IN (\'paid\',\'shipped\',\'delivered\')) DESC, p.created_at DESC',
    default => 'p.featured DESC, p.created_at DESC'
};

$whereSql = implode(' AND ', $where);

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM store_products p LEFT JOIN store_categories c ON p.category_id = c.id WHERE $whereSql");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("SELECT p.*, p.featured::int AS featured, p.is_active::int AS is_active, c.name AS category_name, c.slug AS category_slug FROM store_products p LEFT JOIN store_categories c ON p.category_id = c.id WHERE $whereSql ORDER BY $order LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$products = $stmt->fetchAll();

$catStmt = $pdo->query("
  SELECT c.*, COUNT(p.id) AS product_count
  FROM store_categories c
  LEFT JOIN store_products p ON p.category_id = c.id AND p.is_active = TRUE
  GROUP BY c.id
  ORDER BY c.sort_order
");
$categories = $catStmt->fetchAll();

$currentCategory = null;
if ($categoryFilter) {
    foreach ($categories as $cat) {
        if ($cat['slug'] === $categoryFilter) { $currentCategory = $cat; break; }
    }
}

// Helper to build URL with current filters but optionally override one
function vv_url(array $overrides = []): string {
    $params = $_GET;
    foreach ($overrides as $k => $v) {
        if ($v === null || $v === '') unset($params[$k]);
        else $params[$k] = $v;
    }
    return 'catalog.php' . (empty($params) ? '' : '?' . http_build_query($params));
}

$pageTitle = 'Katalog — VeVit Store' . ($currentCategory ? ' · ' . $currentCategory['name'] : '');
$activeNav = $dealsOnly ? 'deals' : ($sort === 'newest' && !$categoryFilter && !$search ? 'new' : 'catalog');
$searchValue = $search;
include __DIR__ . '/lib/header.php';
?>

<main class="flex-1 w-full max-w-[1200px] mx-auto px-margin py-lg flex flex-col md:flex-row gap-xl">

  <!-- Sidebar / Filters -->
  <aside class="w-full md:w-64 flex-shrink-0 flex flex-col gap-lg">
    <div>
      <h3 class="font-mono-label text-mono-label text-primary mb-md border-b border-outline-variant pb-base uppercase tracking-widest">Filtry</h3>

      <!-- Categories -->
      <div class="mb-lg">
        <h4 class="font-h2 text-h2 mb-sm text-on-surface">Kategorie</h4>
        <div class="flex flex-col gap-sm">
          <a href="<?= h(vv_url(['category' => null, 'page' => null])) ?>" class="flex items-center justify-between gap-sm <?= !$categoryFilter ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' ?> transition-colors group">
            <span class="flex items-center gap-sm font-body-md">
              <span class="material-symbols-outlined text-[18px]">grid_view</span> Vše
            </span>
          </a>
          <?php foreach ($categories as $cat): ?>
          <a href="<?= h(vv_url(['category' => $cat['slug'], 'page' => null])) ?>" class="flex items-center justify-between gap-sm <?= $categoryFilter === $cat['slug'] ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' ?> transition-colors group">
            <span class="flex items-center gap-sm font-body-md">
              <span class="material-symbols-outlined text-[18px]"><?= h($cat['icon'] ?? 'label') ?></span>
              <?= h($cat['name']) ?>
            </span>
            <span class="font-mono-label text-caption text-on-surface-variant"><?= (int)$cat['product_count'] ?></span>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Type -->
      <div class="mb-lg">
        <h4 class="font-h2 text-h2 mb-sm text-on-surface">Typ produktu</h4>
        <div class="flex flex-col gap-sm">
          <a href="<?= h(vv_url(['type' => null, 'page' => null])) ?>" class="flex items-center gap-sm <?= !$typeFilter ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' ?> transition-colors">
            <span class="material-symbols-outlined text-[18px]">all_inclusive</span> Všechny
          </a>
          <a href="<?= h(vv_url(['type' => 'physical', 'page' => null])) ?>" class="flex items-center gap-sm <?= $typeFilter === 'physical' ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' ?> transition-colors">
            <span class="material-symbols-outlined text-[18px]">local_shipping</span> Fyzické
          </a>
          <a href="<?= h(vv_url(['type' => 'digital', 'page' => null])) ?>" class="flex items-center gap-sm <?= $typeFilter === 'digital' ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' ?> transition-colors">
            <span class="material-symbols-outlined text-[18px]">download</span> Digitální
          </a>
        </div>
      </div>

      <!-- Price -->
      <div class="mb-lg">
        <h4 class="font-h2 text-h2 mb-sm text-on-surface">Maximální cena</h4>
        <form method="get" class="flex flex-col gap-sm">
          <?php foreach ($_GET as $k => $v): if ($k === 'max_price' || $k === 'page') continue; ?>
            <input type="hidden" name="<?= h($k) ?>" value="<?= h(is_array($v) ? '' : (string)$v) ?>">
          <?php endforeach; ?>
          <input type="number" name="max_price" min="0" step="100" value="<?= $maxPrice ?: '' ?>" placeholder="Kč" class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-sm py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
          <button type="submit" class="bg-surface-container border border-outline-variant text-on-surface font-mono-label text-caption uppercase py-2 rounded-DEFAULT hover:border-primary hover:text-primary transition-colors">Použít</button>
        </form>
      </div>

      <!-- Deals toggle -->
      <a href="<?= h(vv_url(['deals' => $dealsOnly ? null : 1, 'page' => null])) ?>" class="flex items-center gap-sm <?= $dealsOnly ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' ?> transition-colors mb-lg">
        <span class="material-symbols-outlined text-[18px]"><?= $dealsOnly ? 'check_box' : 'check_box_outline_blank' ?></span>
        Pouze ve slevě
      </a>

      <?php if ($search || $categoryFilter || $typeFilter || $dealsOnly || $maxPrice): ?>
      <a href="catalog.php" class="w-full block text-center py-sm px-md bg-transparent border-2 border-outline-variant text-on-surface font-mono-label text-mono-label rounded-DEFAULT hover:border-primary hover:text-primary transition-colors uppercase">Vymazat filtry</a>
      <?php endif; ?>
    </div>
  </aside>

  <!-- Product Grid -->
  <section class="flex-1 flex flex-col gap-md min-w-0">
    <!-- Heading -->
    <div class="flex flex-col gap-xs mb-md">
      <h1 class="font-display text-h1 text-on-surface">
        <?= $currentCategory ? h($currentCategory['name']) : ($dealsOnly ? 'Slevy' : 'Katalog') ?>
      </h1>
      <?php if ($search): ?>
      <p class="font-body-md text-on-surface-variant">Výsledky vyhledávání pro <span class="text-primary font-bold">„<?= h($search) ?>"</span></p>
      <?php endif; ?>
    </div>

    <!-- Sort bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-md border-b border-outline-variant gap-md">
      <div class="font-body-md text-body-md text-on-surface-variant">Zobrazeno <span class="text-on-surface font-bold"><?= count($products) ?></span> z <span class="text-on-surface font-bold"><?= $total ?></span> produktů</div>
      <form method="get" class="flex items-center gap-sm">
        <span class="font-mono-label text-caption text-on-surface-variant uppercase">Řazení:</span>
        <?php foreach ($_GET as $k => $v): if ($k === 'sort' || $k === 'page') continue; ?>
          <input type="hidden" name="<?= h($k) ?>" value="<?= h(is_array($v) ? '' : (string)$v) ?>">
        <?php endforeach; ?>
        <select name="sort" onchange="this.form.submit()" class="bg-surface-container border border-outline-variant text-on-surface font-body-md rounded-DEFAULT px-md py-sm focus:ring-primary focus:border-primary focus:outline-none">
          <option value="featured" <?= $sort === 'featured' ? 'selected' : '' ?>>Doporučené</option>
          <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Nejnovější</option>
          <option value="cheapest" <?= $sort === 'cheapest' ? 'selected' : '' ?>>Nejlevnější</option>
          <option value="expensive" <?= $sort === 'expensive' ? 'selected' : '' ?>>Nejdražší</option>
        </select>
      </form>
    </div>

    <?php if (empty($products)): ?>
      <div class="text-center py-xl flex flex-col items-center gap-md">
        <span class="material-symbols-outlined text-[64px] text-on-surface-variant/50">search_off</span>
        <h2 class="font-h2 text-h2 text-on-surface">Nic jsme nenašli</h2>
        <p class="font-body-md text-on-surface-variant">Zkuste upravit filtry nebo prohledejte celý katalog.</p>
        <a href="catalog.php" class="bg-primary-container text-on-primary-fixed font-mono-label text-mono-label py-sm px-md rounded-DEFAULT border-2 border-on-primary-fixed neo-shadow uppercase">Zobrazit vše</a>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-gutter">
        <?php foreach ($products as $p) vv_render_product_card($p); ?>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
      <div class="mt-xl flex justify-center items-center gap-sm flex-wrap">
        <?php if ($page > 1): ?>
          <a href="<?= h(vv_url(['page' => $page-1])) ?>" aria-label="Předchozí" class="p-sm border-2 border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary transition-colors flex items-center justify-center rounded-DEFAULT">
            <span class="material-symbols-outlined">chevron_left</span>
          </a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php if ($i === $page): ?>
            <span class="w-10 h-10 border-2 border-primary bg-primary-container text-on-primary-fixed font-mono-label text-mono-label flex items-center justify-center rounded-DEFAULT neo-shadow"><?= $i ?></span>
          <?php else: ?>
            <a href="<?= h(vv_url(['page' => $i])) ?>" class="w-10 h-10 border-2 border-outline-variant text-on-surface hover:border-primary hover:text-primary transition-colors font-mono-label text-mono-label flex items-center justify-center rounded-DEFAULT"><?= $i ?></a>
          <?php endif; ?>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
          <a href="<?= h(vv_url(['page' => $page+1])) ?>" aria-label="Další" class="p-sm border-2 border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary transition-colors flex items-center justify-center rounded-DEFAULT">
            <span class="material-symbols-outlined">chevron_right</span>
          </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    <?php endif; ?>
  </section>
</main>

<?php include __DIR__ . '/lib/footer.php'; ?>
