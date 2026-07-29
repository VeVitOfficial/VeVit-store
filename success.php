<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/orders/OrderAccessService.php';

header('Cache-Control: no-store, private');
header('Pragma: no-cache');
header('Referrer-Policy: no-referrer');

$publicId = $_GET['order'] ?? '';
$order = null;
$items = [];
if (is_string($publicId) && preg_match('/^[a-f0-9]{32}$/', $publicId)) {
    $statement = $pdo->prepare('SELECT * FROM store_orders WHERE public_id = ? LIMIT 1');
    $statement->execute([$publicId]);
    $candidate = $statement->fetch();
    $user = getCurrentUser();
    $access = new OrderAccessService(new DateTimeImmutable('now', new DateTimeZone('UTC')));
    if ($candidate && $access->canAccess($candidate, $user, OrderAccessService::sessionGrant($publicId), !empty($_SESSION['admin']), $publicId)) {
        $order = $candidate;
        $itemsStatement = $pdo->prepare('SELECT id, product_name, product_type, quantity, unit_price FROM store_order_items WHERE order_id = ? ORDER BY id');
        $itemsStatement->execute([$order['id']]);
        $items = $itemsStatement->fetchAll();
    }
}
if ($order === null) {
    store_log_security_event('order_access_rejected', ['public_id_prefix' => is_string($publicId) ? substr($publicId, 0, 8) : 'invalid']);
    http_response_code(404);
    exit('Stránka nebyla nalezena.');
}

$statusLabels = [
    'pending' => 'Platba se zpracovává',
    'pending_checkout' => 'Čekáme na zahájení platby',
    'awaiting_payment' => 'Čekáme na potvrzení platby',
    'paid' => 'Objednávka zaplacena',
    'processing' => 'Objednávka se zpracovává',
    'shipped' => 'Objednávka odeslána',
    'delivered' => 'Objednávka doručena',
    'manual_review' => 'Platba přijata — objednávku ověřujeme',
    'cancelled' => 'Objednávka zrušena',
    'refunded' => 'Platba vrácena',
];
$canRequestDownloads = ($order['payment_status'] ?? null) === 'paid'
    && ($order['fulfillment_status'] ?? null) !== 'manual_review'
    && in_array($order['status'], ['paid', 'processing', 'shipped', 'delivered'], true);
$pageTitle = 'Stav objednávky — VeVit Store';
$activeNav = '';
include __DIR__ . '/lib/header.php';
$csrf = store_csrf_token('download');
?>
<main class="flex-1 w-full max-w-[720px] mx-auto px-margin py-xl flex flex-col gap-md">
  <div class="flex flex-col items-center text-center gap-md">
    <span class="font-mono-label text-mono-label text-primary uppercase tracking-widest">Objednávka</span>
    <h1 class="font-display text-display text-on-surface"><?= h($statusLabels[$order['status']] ?? 'Stav objednávky') ?></h1>
    <p class="font-body-lg text-body-lg text-on-surface-variant max-w-md">Stav platby je ověřován na serveru. Návrat z platební brány sám o sobě platbu nepotvrzuje.</p>
    <div class="font-mono-label text-mono-label text-on-surface bg-surface-container border border-outline-variant rounded-DEFAULT px-md py-sm">Číslo objednávky: <strong class="text-primary"><?= h($order['order_number']) ?></strong></div>
  </div>
  <section class="bg-surface-container border border-outline-variant rounded-xl p-md">
    <h2 class="font-h2 text-h2 text-on-surface mb-md">Souhrn</h2>
    <?php foreach ($items as $item): ?>
      <div class="flex justify-between gap-sm py-sm border-b border-outline-variant/50 last:border-0"><span class="text-on-surface"><?= h($item['product_name']) ?> ×<?= (int) $item['quantity'] ?></span><span class="text-on-surface-variant"><?= h((string) $item['unit_price']) ?> Kč</span></div>
      <?php if ($canRequestDownloads && $item['product_type'] === 'digital'): ?><button class="request-download mt-sm bg-primary-container text-on-primary-fixed font-mono-label text-mono-label py-sm px-md rounded" data-item-id="<?= (int) $item['id'] ?>">Připravit stažení</button><?php endif; ?>
    <?php endforeach; ?>
  </section>
</main>
<script>
document.querySelectorAll('.request-download').forEach(button => button.addEventListener('click', async () => {
  const response = await fetch('api/request-download.php', {method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-Token':<?= json_encode($csrf) ?>}, body: JSON.stringify({order:<?= json_encode($publicId) ?>, item_id:Number(button.dataset.itemId)})});
  const data = await response.json();
  if (!data.download?.token) { window.showToast?.(data.error?.message || 'Stažení není k dispozici.', 'error'); return; }
  const form = document.createElement('form'); form.method = 'POST'; form.action = 'download.php';
  [['token', data.download.token], ['csrf', <?= json_encode($csrf) ?>]].forEach(([name, value]) => { const input = document.createElement('input'); input.type='hidden'; input.name=name; input.value=value; form.appendChild(input); });
  document.body.appendChild(form); form.submit(); form.remove();
}));
</script>
<?php include __DIR__ . '/lib/footer.php'; ?>
