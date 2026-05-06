<?php
require_once __DIR__ . '/config.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT p.*, p.featured::int AS featured, p.is_active::int AS is_active, c.name AS category_name, c.slug AS category_slug FROM store_products p LEFT JOIN store_categories c ON p.category_id = c.id WHERE p.slug = ? AND p.is_active = TRUE");
$stmt->execute([$slug]);
$product = $stmt->fetch();
if (!$product) { http_response_code(404); $pageTitle='Nenalezeno'; include __DIR__.'/lib/header.php'; ?>
<main class="flex-1 max-w-[1200px] mx-auto px-margin py-xl text-center flex flex-col items-center gap-md">
  <span class="material-symbols-outlined text-[80px] text-on-surface-variant/50">search_off</span>
  <h1 class="font-display text-h1 text-on-surface">Produkt nenalezen</h1>
  <p class="font-body-md text-on-surface-variant">Tento produkt už neexistuje nebo byl odstraněn.</p>
  <a href="catalog.php" class="bg-primary-container text-on-primary-fixed font-mono-label text-mono-label py-sm px-md rounded-DEFAULT border-2 border-on-primary-fixed neo-shadow uppercase">Zpět do katalogu</a>
</main>
<?php include __DIR__.'/lib/footer.php'; exit; }

$isDigital = $product['type'] === 'digital';
$outOfStock = vv_product_is_out_of_stock($product);
$hasSale = vv_product_has_sale($product);
$price = vv_product_price($product);
$catBg = vv_category_bg($product['category_slug']);

$relatedStmt = $pdo->prepare("SELECT p.*, p.featured::int AS featured, p.is_active::int AS is_active, c.name AS category_name, c.slug AS category_slug FROM store_products p LEFT JOIN store_categories c ON p.category_id = c.id WHERE p.is_active = TRUE AND p.id != ? AND (p.category_id = ? OR p.type = ?) ORDER BY p.featured DESC, p.created_at DESC LIMIT 4");
$relatedStmt->execute([$product['id'], $product['category_id'], $product['type']]);
$related = $relatedStmt->fetchAll();

$pageTitle = $product['name'] . ' — VeVit Store';
$activeNav = 'catalog';
include __DIR__ . '/lib/header.php';
?>

<main class="flex-1 w-full max-w-[1200px] mx-auto px-margin py-xl">

  <!-- Breadcrumbs -->
  <div class="flex flex-wrap items-center gap-2 mb-lg font-mono-label text-caption text-on-surface-variant uppercase">
    <a class="hover:text-primary transition-colors" href="index.php">Domů</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <a class="hover:text-primary transition-colors" href="catalog.php">Katalog</a>
    <?php if ($product['category_slug']): ?>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <a class="hover:text-primary transition-colors" href="catalog.php?category=<?= h($product['category_slug']) ?>"><?= h($product['category_name']) ?></a>
    <?php endif; ?>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-on-surface normal-case"><?= h($product['name']) ?></span>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter lg:gap-xl">

    <!-- Gallery -->
    <div class="md:col-span-7 flex flex-col gap-sm">
      <div class="w-full aspect-[4/5] <?= $catBg ?> rounded-xl overflow-hidden border border-outline-variant relative group">
        <div class="absolute inset-0 flex items-center justify-center">
          <span class="font-mono-label text-mono-label text-white/40 uppercase tracking-widest text-xl"><?= h($product['category_name'] ?? 'VeVit') ?></span>
        </div>
        <?php if ($product['featured']): ?><div class="absolute top-4 left-4 border border-primary text-primary px-3 py-1 font-mono-label text-caption bg-background/80 backdrop-blur-sm rounded uppercase">Nové</div><?php endif; ?>
        <?php if ($hasSale): ?><div class="absolute top-4 right-4 border border-error text-error px-3 py-1 font-mono-label text-caption bg-background/80 backdrop-blur-sm rounded uppercase">Sleva</div><?php endif; ?>
      </div>
      <div class="grid grid-cols-3 gap-sm">
        <?php for ($i = 0; $i < 3; $i++): ?>
          <div class="aspect-square <?= $catBg ?> rounded border border-outline-variant/50 opacity-70 hover:opacity-100 hover:border-primary transition-all cursor-pointer flex items-center justify-center">
            <span class="material-symbols-outlined text-white/30">image</span>
          </div>
        <?php endfor; ?>
      </div>
    </div>

    <!-- Details -->
    <div class="md:col-span-5 flex flex-col">
      <span class="font-mono-label text-caption text-primary tracking-widest uppercase mb-sm"><?= h($product['category_name'] ?? 'VeVit') ?></span>
      <h1 class="font-display text-display text-on-surface mb-sm leading-tight"><?= h($product['name']) ?></h1>

      <!-- Price -->
      <div class="flex items-baseline gap-sm mb-md">
        <span class="font-display text-h1 text-primary"><?= vv_format_price($price) ?></span>
        <?php if ($hasSale): ?>
          <span class="font-body-md text-on-surface-variant line-through"><?= vv_format_price((float)$product['price']) ?></span>
          <span class="font-mono-label text-caption text-error border border-error px-2 py-1 rounded uppercase">−<?= round((1 - $product['sale_price'] / $product['price']) * 100) ?>%</span>
        <?php endif; ?>
      </div>

      <p class="font-body-lg text-body-lg text-on-surface-variant mb-lg"><?= nl2br(h($product['short_desc'] ?? '')) ?></p>

      <!-- Stock indicator -->
      <?php if (!$isDigital && $product['stock'] !== null): ?>
      <div class="flex items-center gap-sm mb-md font-mono-label text-mono-label">
        <?php if ($product['stock'] > 0): ?>
          <span class="w-2 h-2 rounded-full bg-primary"></span>
          <span class="text-primary uppercase">Skladem (<?= (int)$product['stock'] ?> ks)</span>
        <?php else: ?>
          <span class="w-2 h-2 rounded-full bg-error"></span>
          <span class="text-error uppercase">Vyprodáno</span>
        <?php endif; ?>
      </div>
      <?php elseif ($isDigital): ?>
      <div class="flex items-center gap-sm mb-md font-mono-label text-mono-label">
        <span class="w-2 h-2 rounded-full bg-primary"></span>
        <span class="text-primary uppercase">Okamžité stažení po platbě</span>
      </div>
      <?php endif; ?>

      <hr class="border-outline-variant mb-lg"/>

      <!-- Action buttons -->
      <div class="flex flex-wrap gap-sm mb-lg">
        <?php if ($outOfStock): ?>
          <button disabled class="flex-grow opacity-50 cursor-not-allowed bg-surface-container-high text-on-surface-variant font-mono-label text-mono-label py-4 px-md rounded border-2 border-outline-variant flex justify-center items-center gap-sm uppercase">
            <span class="material-symbols-outlined">block</span> Vyprodáno
          </button>
        <?php elseif ($isDigital): ?>
          <button onclick="buyNow()" class="flex-grow bg-primary-container text-on-primary-fixed font-mono-label text-mono-label py-4 px-md rounded border-2 border-on-primary-fixed hard-shadow-primary hard-shadow-primary-active flex justify-center items-center gap-sm uppercase">
            <span class="material-symbols-outlined">download</span> Koupit a stáhnout
          </button>
          <button onclick="addToCart()" aria-label="Do košíku" class="bg-transparent border-2 border-outline-variant text-on-surface font-mono-label text-mono-label py-4 px-md rounded hover:border-primary hover:text-primary transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined">add_shopping_cart</span>
          </button>
        <?php else: ?>
          <div class="flex items-center bg-surface border-2 border-outline-variant rounded h-[60px]">
            <button onclick="changeQty(-1)" aria-label="Méně" class="w-12 h-full flex items-center justify-center text-on-surface hover:text-primary transition-colors border-r border-outline-variant"><span class="material-symbols-outlined">remove</span></button>
            <span id="qtyVal" class="font-mono-label text-mono-label w-12 text-center">1</span>
            <button onclick="changeQty(1)" aria-label="Více" class="w-12 h-full flex items-center justify-center text-on-surface hover:text-primary transition-colors border-l border-outline-variant"><span class="material-symbols-outlined">add</span></button>
          </div>
          <button onclick="addToCart()" class="flex-grow bg-primary-container text-on-primary-fixed font-mono-label text-mono-label py-4 px-md rounded border-2 border-on-primary-fixed hard-shadow-primary hard-shadow-primary-active flex justify-center items-center gap-sm uppercase">
            <span class="material-symbols-outlined">shopping_bag</span> Do košíku
          </button>
        <?php endif; ?>
      </div>

      <!-- Accordion -->
      <div class="flex flex-col border-t border-outline-variant">
        <details class="group border-b border-outline-variant">
          <summary class="py-md flex justify-between items-center cursor-pointer list-none">
            <span class="font-mono-label text-mono-label text-on-surface group-hover:text-primary transition-colors uppercase">Popis produktu</span>
            <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary group-open:rotate-180 transition-transform">expand_more</span>
          </summary>
          <div class="pb-md font-body-md text-on-surface-variant whitespace-pre-line"><?= h($product['description'] ?? $product['short_desc'] ?? 'Bez popisu.') ?></div>
        </details>
        <details class="group border-b border-outline-variant">
          <summary class="py-md flex justify-between items-center cursor-pointer list-none">
            <span class="font-mono-label text-mono-label text-on-surface group-hover:text-primary transition-colors uppercase">Doprava &amp; vrácení</span>
            <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary group-open:rotate-180 transition-transform">expand_more</span>
          </summary>
          <div class="pb-md font-body-md text-on-surface-variant space-y-xs">
            <?php if ($isDigital): ?>
              <p>Digitální produkt — okamžité stažení po platbě. Odkaz platí 72 h, max. 5 stažení.</p>
            <?php else: ?>
              <p>Doručení do 2 pracovních dnů. Doprava ZDARMA u objednávek nad 1 000 Kč, jinak 99 Kč.</p>
              <p>14 denní lhůta na vrácení bez udání důvodu.</p>
            <?php endif; ?>
          </div>
        </details>
        <details class="group border-b border-outline-variant">
          <summary class="py-md flex justify-between items-center cursor-pointer list-none">
            <span class="font-mono-label text-mono-label text-on-surface group-hover:text-primary transition-colors uppercase">Specifikace</span>
            <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary group-open:rotate-180 transition-transform">expand_more</span>
          </summary>
          <div class="pb-md font-body-md text-on-surface-variant">
            <dl class="grid grid-cols-2 gap-xs">
              <dt class="font-mono-label text-caption text-on-surface-variant uppercase">Typ</dt>
              <dd class="text-on-surface"><?= $isDigital ? 'Digitální' : 'Fyzický' ?></dd>
              <dt class="font-mono-label text-caption text-on-surface-variant uppercase">Kategorie</dt>
              <dd class="text-on-surface"><?= h($product['category_name'] ?? '—') ?></dd>
              <?php if (!$isDigital && $product['stock'] !== null): ?>
                <dt class="font-mono-label text-caption text-on-surface-variant uppercase">Sklad</dt>
                <dd class="text-on-surface"><?= (int)$product['stock'] ?> ks</dd>
              <?php endif; ?>
            </dl>
          </div>
        </details>
      </div>
    </div>
  </div>

  <!-- Related -->
  <?php if (!empty($related)): ?>
  <section class="mt-xl pt-xl border-t border-outline-variant">
    <div class="flex justify-between items-end mb-lg">
      <h2 class="font-display text-h1 text-on-surface">Podobné produkty</h2>
      <a href="catalog.php<?= $product['category_slug'] ? '?category=' . h($product['category_slug']) : '' ?>" class="font-mono-label text-mono-label text-primary flex items-center gap-1 hover:translate-x-1 transition-transform uppercase">Zobrazit vše <span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
      <?php foreach ($related as $r) vv_render_product_card($r); ?>
    </div>
  </section>
  <?php endif; ?>
</main>

<script>
const __product = <?= json_encode([
  'id' => (int)$product['id'],
  'name' => $product['name'],
  'price' => (float)$product['price'],
  'sale_price' => $product['sale_price'] ? (float)$product['sale_price'] : null,
  'type' => $product['type'],
  'slug' => $product['slug']
], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;
let __qty = 1;
function changeQty(delta) {
  __qty = Math.max(1, __qty + delta);
  document.getElementById('qtyVal').textContent = __qty;
}
function addToCart() { Cart.add(__product, __qty); }
function buyNow() { Cart.add(__product, 1); window.location.href = 'cart.php'; }
</script>

<?php include __DIR__ . '/lib/footer.php'; ?>
