<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Platba zrušena — VeVit Store';
$activeNav = 'cart';
include __DIR__ . '/lib/header.php';
?>

<main class="flex-1 w-full max-w-[720px] mx-auto px-margin py-xl flex flex-col items-center text-center gap-md">
  <div class="w-20 h-20 rounded-full bg-error/15 border border-error flex items-center justify-center">
    <span class="material-symbols-outlined text-[44px] text-error icon-filled">cancel</span>
  </div>
  <h1 class="font-display text-display text-on-surface">Platba byla zrušena</h1>
  <p class="font-body-lg text-body-lg text-on-surface-variant max-w-md">
    Tvá objednávka nebyla dokončena. Obsah košíku zůstal uložen, můžeš to zkusit znovu nebo si nechat čas na rozmyšlenou.
  </p>
  <div class="flex flex-col sm:flex-row gap-sm mt-md">
    <a href="cart.php" class="bg-transparent border-2 border-outline-variant text-on-surface font-mono-label text-mono-label py-sm px-md rounded hover:border-primary hover:text-primary transition-colors uppercase inline-flex items-center justify-center gap-xs">
      <span class="material-symbols-outlined">shopping_bag</span> Zpět do košíku
    </a>
    <a href="checkout.php" class="bg-primary-container text-on-primary-fixed font-mono-label text-mono-label py-sm px-md rounded border-2 border-on-primary-fixed neo-shadow uppercase inline-flex items-center justify-center gap-xs">
      <span class="material-symbols-outlined">replay</span> Zkusit znovu
    </a>
  </div>
</main>

<?php include __DIR__ . '/lib/footer.php'; ?>
