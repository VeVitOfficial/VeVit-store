<?php
require_once __DIR__ . '/config.php';

// Featured / trending products (8)
$trendingStmt = $pdo->query("
  SELECT p.*, p.featured::int AS featured, p.is_active::int AS is_active,
         c.name AS category_name, c.slug AS category_slug
  FROM store_products p
  LEFT JOIN store_categories c ON p.category_id = c.id
  WHERE p.is_active = TRUE
  ORDER BY p.featured DESC, p.created_at DESC
  LIMIT 8
");
$trending = $trendingStmt->fetchAll();

// Categories with item counts
$catStmt = $pdo->query("
  SELECT c.*, COUNT(p.id) AS product_count
  FROM store_categories c
  LEFT JOIN store_products p ON p.category_id = c.id AND p.is_active = TRUE
  GROUP BY c.id
  ORDER BY c.sort_order
");
$categories = $catStmt->fetchAll();

// Hero featured product (first featured one)
$heroStmt = $pdo->query("
  SELECT p.*, p.featured::int AS featured, p.is_active::int AS is_active,
         c.name AS category_name, c.slug AS category_slug
  FROM store_products p
  LEFT JOIN store_categories c ON p.category_id = c.id
  WHERE p.is_active = TRUE AND p.featured = TRUE
  ORDER BY p.created_at DESC LIMIT 1
");
$hero = $heroStmt->fetch();

$pageTitle = 'VeVit Store — Digital First Lifestyle';
$activeNav = 'home';
include __DIR__ . '/lib/header.php';
?>

<!-- Main Content -->
<main class="flex-1 max-w-[1200px] mx-auto px-margin w-full">

  <!-- Hero -->
  <section class="py-xl flex flex-col md:flex-row gap-gutter items-center justify-between">
    <div class="flex-1 space-y-md">
      <span class="font-mono-label text-mono-label text-primary border border-primary px-3 py-1 rounded-full uppercase tracking-widest inline-block">Digital First</span>
      <h1 class="font-display text-display text-on-surface leading-tight">
        Vylepši svůj <br/>
        <span class="text-primary">digitální život</span>
      </h1>
      <p class="font-body-lg text-body-lg text-on-surface-variant max-w-md">
        Kurátorsky vybraná tech, výrazný merch a digitální nástroje pro tvůrce. Rychlá pokladna, žádné zbytečnosti.
      </p>
      <div class="pt-sm flex flex-wrap gap-sm">
        <a href="catalog.php" class="bg-primary-container text-on-primary-fixed font-mono-label text-mono-label px-lg py-sm border-2 border-on-primary-fixed rounded hard-shadow-primary hard-shadow-primary-active inline-flex items-center gap-xs">
          NAKUPOVAT <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </a>
        <a href="catalog.php?deals=1" class="bg-transparent text-on-surface font-mono-label text-mono-label px-lg py-sm border border-outline rounded hover:border-primary hover:text-primary transition-colors inline-flex items-center gap-xs">
          ZOBRAZIT SLEVY
        </a>
      </div>
    </div>

    <?php if ($hero): ?>
    <a href="product.php?slug=<?= h($hero['slug']) ?>" class="flex-1 w-full block group">
      <div class="aspect-square <?= vv_category_bg($hero['category_slug']) ?> rounded-xl border border-outline-variant overflow-hidden relative">
        <div class="absolute inset-0 flex items-center justify-center">
          <span class="font-mono-label text-mono-label text-white/40 uppercase tracking-widest text-2xl"><?= h($hero['category_name'] ?? 'VeVit') ?></span>
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-background via-background/30 to-transparent"></div>
        <div class="absolute bottom-md left-md bg-surface/90 backdrop-blur p-sm border border-outline-variant rounded">
          <span class="font-mono-label text-mono-label text-primary block uppercase">Doporučujeme</span>
          <span class="font-h2 text-h2 text-on-surface"><?= h($hero['name']) ?></span>
        </div>
      </div>
    </a>
    <?php else: ?>
    <div class="flex-1 w-full">
      <div class="aspect-square bg-surface-container rounded-xl border border-outline-variant flex items-center justify-center">
        <span class="material-symbols-outlined text-[120px] text-primary/30">storefront</span>
      </div>
    </div>
    <?php endif; ?>
  </section>

  <!-- Categories (Bento Grid) -->
  <?php if (!empty($categories)): ?>
  <section class="py-xl">
    <div class="flex justify-between items-end mb-md">
      <h2 class="font-display text-h1 text-on-surface">Kategorie</h2>
      <a href="catalog.php" class="font-mono-label text-mono-label text-primary flex items-center gap-1 hover:translate-x-1 transition-transform">Vše <span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter min-h-[420px] md:h-[600px]">
      <?php foreach ($categories as $i => $cat):
        $bg = vv_category_bg($cat['slug']);
        $isLarge = $i === 0;
      ?>
      <a href="catalog.php?category=<?= h($cat['slug']) ?>" class="<?= $isLarge ? 'md:col-span-2 md:row-span-2' : '' ?> relative group rounded-xl overflow-hidden border border-outline-variant hard-shadow-hover block <?= $bg ?>">
        <div class="absolute inset-0 bg-gradient-to-br from-black/20 via-black/40 to-black/70"></div>
        <div class="absolute inset-0 flex items-center justify-center opacity-30 group-hover:opacity-50 transition-opacity">
          <span class="material-symbols-outlined text-[120px] text-white"><?= h($cat['icon'] ?? 'category') ?></span>
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-background via-background/20 to-transparent"></div>
        <div class="absolute bottom-margin left-margin right-margin">
          <h3 class="font-display <?= $isLarge ? 'text-h1' : 'text-h2' ?> text-primary"><?= h($cat['name']) ?></h3>
          <p class="font-mono-label text-mono-label text-on-surface-variant mt-xs uppercase">
            <?= (int)$cat['product_count'] ?> produktů
          </p>
        </div>
        <div class="absolute top-margin right-margin bg-background/80 backdrop-blur p-2 rounded-full border border-outline-variant">
          <span class="material-symbols-outlined text-primary"><?= h($cat['icon'] ?? 'category') ?></span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- Trending -->
  <?php if (!empty($trending)): ?>
  <section class="py-xl">
    <div class="flex justify-between items-end mb-lg">
      <h2 class="font-display text-h1 text-on-surface">Žhavé novinky</h2>
      <a href="catalog.php?sort=newest" class="font-mono-label text-mono-label text-primary flex items-center gap-1 hover:translate-x-1 transition-transform">Vše <span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-gutter">
      <?php foreach ($trending as $p) vv_render_product_card($p); ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- Value Props -->
  <section class="py-xl grid grid-cols-1 md:grid-cols-3 gap-gutter">
    <div class="bg-surface-container border border-outline-variant rounded-xl p-md">
      <div class="w-12 h-12 rounded bg-primary/15 flex items-center justify-center mb-sm">
        <span class="material-symbols-outlined text-primary">local_shipping</span>
      </div>
      <h3 class="font-h2 text-h2 text-on-surface mb-xs">Doprava zdarma</h3>
      <p class="font-body-md text-on-surface-variant">U objednávek nad 1 000 Kč. Doručení do 2 pracovních dnů po ČR.</p>
    </div>
    <div class="bg-surface-container border border-outline-variant rounded-xl p-md">
      <div class="w-12 h-12 rounded bg-primary/15 flex items-center justify-center mb-sm">
        <span class="material-symbols-outlined text-primary">bolt</span>
      </div>
      <h3 class="font-h2 text-h2 text-on-surface mb-xs">Okamžité stažení</h3>
      <p class="font-body-md text-on-surface-variant">Digitální produkty po platbě hned ke stažení. Žádné čekání.</p>
    </div>
    <div class="bg-surface-container border border-outline-variant rounded-xl p-md">
      <div class="w-12 h-12 rounded bg-primary/15 flex items-center justify-center mb-sm">
        <span class="material-symbols-outlined text-primary">verified</span>
      </div>
      <h3 class="font-h2 text-h2 text-on-surface mb-xs">Bezpečná platba</h3>
      <p class="font-body-md text-on-surface-variant">Stripe checkout, šifrovaná platba kartou nebo Apple Pay.</p>
    </div>
  </section>
</main>

<?php include __DIR__ . '/lib/footer.php'; ?>
