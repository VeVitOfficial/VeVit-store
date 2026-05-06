<?php
require_once __DIR__ . '/config.php';

$orderNumber = $_GET['order'] ?? '';
$items = [];
$order = null;

if ($orderNumber) {
  $stmt = $pdo->prepare("SELECT o.*, i.product_name, i.product_type, i.download_token, i.quantity AS item_qty, i.unit_price FROM store_orders o LEFT JOIN store_order_items i ON o.id = i.order_id WHERE o.order_number = ?");
  $stmt->execute([$orderNumber]);
  $rows = $stmt->fetchAll();
  if ($rows) {
    $order = $rows[0];
    $items = array_filter($rows, fn($r) => !empty($r['product_name']));
  }
}

$hasDigital = false;
foreach ($items as $it) {
  if ($it['product_type'] === 'digital' && $it['download_token']) { $hasDigital = true; break; }
}

$pageTitle = 'Objednávka potvrzena — VeVit Store';
$activeNav = '';
include __DIR__ . '/lib/header.php';
?>

<main class="flex-1 w-full max-w-[720px] mx-auto px-margin py-xl flex flex-col gap-md">

  <!-- Hero -->
  <div class="flex flex-col items-center text-center gap-md">
    <div class="w-20 h-20 rounded-full bg-primary/15 border border-primary flex items-center justify-center">
      <span class="material-symbols-outlined text-[44px] text-primary icon-filled">check_circle</span>
    </div>
    <span class="font-mono-label text-mono-label text-primary uppercase tracking-widest">Krok 3 / 3</span>
    <h1 class="font-display text-display text-on-surface">Hotovo!</h1>
    <p class="font-body-lg text-body-lg text-on-surface-variant max-w-md">Platba proběhla v pořádku. Děkujeme za nákup u VeVit Store.</p>
    <?php if ($orderNumber): ?>
    <div class="font-mono-label text-mono-label text-on-surface bg-surface-container border border-outline-variant rounded-DEFAULT px-md py-sm">
      Číslo objednávky: <strong class="text-primary"><?= h($orderNumber) ?></strong>
    </div>
    <?php endif; ?>
  </div>

  <?php if ($hasDigital): ?>
  <section class="bg-surface-container border border-outline-variant rounded-xl p-md mt-lg">
    <div class="flex items-center gap-sm mb-md">
      <span class="material-symbols-outlined text-primary">download</span>
      <h2 class="font-h2 text-h2 text-on-surface">Digitální produkty ke stažení</h2>
    </div>
    <div class="flex flex-col gap-sm">
      <?php foreach ($items as $item): if ($item['product_type'] !== 'digital' || !$item['download_token']) continue; ?>
      <div class="flex flex-col sm:flex-row gap-sm justify-between items-start sm:items-center pb-sm border-b border-outline-variant/50 last:border-0 last:pb-0">
        <div class="flex-1">
          <div class="font-body-md text-on-surface"><?= h($item['product_name']) ?></div>
          <div class="font-caption text-caption text-on-surface-variant">Odkaz platí 72 h, max. 5 stažení</div>
        </div>
        <a href="download.php?token=<?= h($item['download_token']) ?>" class="bg-primary-container text-on-primary-fixed font-mono-label text-mono-label py-sm px-md rounded border-2 border-on-primary-fixed neo-shadow uppercase inline-flex items-center gap-xs">
          <span class="material-symbols-outlined text-[16px]">download</span> Stáhnout
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <?php if ($order && !empty($items)): ?>
  <section class="bg-surface-container border border-outline-variant rounded-xl p-md mt-lg">
    <h2 class="font-h2 text-h2 text-on-surface mb-md pb-sm border-b border-outline-variant">Souhrn</h2>
    <div class="flex flex-col gap-sm">
      <?php foreach ($items as $item): ?>
      <div class="flex justify-between items-center font-body-md text-body-md">
        <span class="text-on-surface"><?= h($item['product_name']) ?> <span class="text-on-surface-variant">×<?= (int)$item['item_qty'] ?></span></span>
        <span class="font-mono-label text-mono-label text-on-surface"><?= vv_format_price((float)$item['unit_price'] * (int)$item['item_qty']) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="flex justify-between items-center pt-md mt-md border-t border-outline-variant">
      <span class="font-h2 text-h2 text-on-surface">Celkem</span>
      <span class="font-display text-h1 text-primary"><?= vv_format_price((float)$order['total']) ?></span>
    </div>
    <?php if (!empty($order['customer_email'])): ?>
    <p class="font-caption text-caption text-on-surface-variant mt-md">Potvrzení objednávky odesláno na <span class="text-on-surface"><?= h($order['customer_email']) ?></span></p>
    <?php endif; ?>
  </section>
  <?php endif; ?>

  <div class="flex justify-center mt-md">
    <a href="catalog.php" class="bg-transparent border-2 border-outline-variant text-on-surface font-mono-label text-mono-label py-sm px-md rounded hover:border-primary hover:text-primary transition-colors uppercase inline-flex items-center gap-xs">
      <span class="material-symbols-outlined">arrow_back</span> Zpět do katalogu
    </a>
  </div>
</main>

<script>
// Clear cart after successful payment
document.addEventListener('DOMContentLoaded', () => {
  if (window.Cart) Cart.clear();
});
</script>

<?php include __DIR__ . '/lib/footer.php'; ?>
