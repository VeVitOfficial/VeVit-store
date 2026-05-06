<?php
// Shared footer + mobile bottom nav + JS includes
$activeNav = $activeNav ?? '';
?>
<!-- Footer -->
<footer class="bg-surface-container-lowest border-t border-outline-variant w-full py-xl px-margin flex flex-col md:flex-row justify-between items-center gap-gutter mt-auto">
  <div class="font-display text-h2 font-black text-on-surface">VeVit Store</div>
  <div class="flex flex-wrap gap-md justify-center">
    <a class="text-on-surface-variant hover:text-primary transition-colors hover:translate-x-1 font-body-md" href="#">Ochrana soukromí</a>
    <a class="text-on-surface-variant hover:text-primary transition-colors hover:translate-x-1 font-body-md" href="#">Obchodní podmínky</a>
    <a class="text-on-surface-variant hover:text-primary transition-colors hover:translate-x-1 font-body-md" href="#">Doprava</a>
    <a class="text-on-surface-variant hover:text-primary transition-colors hover:translate-x-1 font-body-md" href="#">Kontakt</a>
  </div>
  <div class="font-body-md text-on-surface-variant">© <?= date('Y') ?> VeVit Store</div>
</footer>

<!-- BottomNavBar (Mobile) -->
<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 py-2 md:hidden bg-surface-container border-t border-outline-variant rounded-t-xl shadow-lg">
  <a href="index.php" aria-label="Domů" class="<?= $activeNav === 'home' ? 'bg-primary-container text-on-primary-container rounded-full px-4 py-1 border border-on-primary-container shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]' : 'text-on-surface-variant hover:text-primary p-2' ?> flex flex-col items-center justify-center transition-colors">
    <span class="material-symbols-outlined text-[24px]">home</span>
    <span class="font-mono-label text-caption mt-xs">Domů</span>
  </a>
  <a href="catalog.php" aria-label="Katalog" class="<?= ($activeNav === 'catalog' || $activeNav === 'deals' || $activeNav === 'new') ? 'bg-primary-container text-on-primary-container rounded-full px-4 py-1 border border-on-primary-container shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]' : 'text-on-surface-variant hover:text-primary p-2' ?> flex flex-col items-center justify-center transition-colors">
    <span class="material-symbols-outlined text-[24px]">storefront</span>
    <span class="font-mono-label text-caption mt-xs">Katalog</span>
  </a>
  <a href="cart.php" aria-label="Košík" class="<?= $activeNav === 'cart' ? 'bg-primary-container text-on-primary-container rounded-full px-4 py-1 border border-on-primary-container shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]' : 'text-on-surface-variant hover:text-primary p-2' ?> flex flex-col items-center justify-center transition-colors relative">
    <span class="material-symbols-outlined text-[24px]">shopping_bag</span>
    <span class="nav-cart-badge absolute top-0 right-3 min-w-4 h-4 px-1 bg-primary text-on-primary text-[10px] font-bold rounded-full flex items-center justify-center" style="display:none">0</span>
    <span class="font-mono-label text-caption mt-xs">Košík</span>
  </a>
  <?php if ($currentUser): ?>
  <a href="logout.php" aria-label="Odhlásit se" class="text-on-surface-variant hover:text-error p-2 flex flex-col items-center justify-center transition-colors">
    <span class="material-symbols-outlined text-[24px]">logout</span>
    <span class="font-mono-label text-caption mt-xs">Odhlásit</span>
  </a>
  <?php else: ?>
  <button type="button" onclick="openLoginModal()" aria-label="Přihlášení" class="text-on-surface-variant hover:text-primary p-2 flex flex-col items-center justify-center transition-colors">
    <span class="material-symbols-outlined text-[24px]">person</span>
    <span class="font-mono-label text-caption mt-xs">Účet</span>
  </button>
  <?php endif; ?>
</nav>

<script src="assets/js/cart.js"></script>
<script src="assets/js/app.js"></script>
</body></html>
