<?php
// Shared header / nav for VeVit Store
// Usage: set $pageTitle, $activeNav (one of: home, catalog, deals, new, cart) before include.
$pageTitle = $pageTitle ?? 'VeVit Store';
$activeNav = $activeNav ?? '';
$searchValue = $searchValue ?? '';
$currentUser = function_exists('getCurrentUser') ? getCurrentUser() : null;
?>
<!DOCTYPE html>
<html class="dark" lang="cs">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle) ?></title>
<?php include __DIR__ . '/tw_config.php'; ?>
</head>
<body class="bg-background text-on-surface font-body-md text-body-md antialiased min-h-screen flex flex-col pb-24 md:pb-0">

<!-- TopNavBar (Web) -->
<nav class="hidden md:flex sticky top-0 z-50 justify-between items-center w-full px-margin py-base bg-background/80 backdrop-blur-md border-b border-outline-variant">
  <a href="index.php" class="font-display text-h1 font-extrabold text-primary tracking-tighter">VeVit Store</a>
  <div class="flex gap-lg items-center">
    <a href="index.php" class="<?= $activeNav === 'home' ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-body-md hover:text-on-surface' ?> transition-colors px-2 py-1">Domů</a>
    <a href="catalog.php" class="<?= $activeNav === 'catalog' ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-body-md hover:text-on-surface' ?> transition-colors px-2 py-1">Katalog</a>
    <a href="catalog.php?sort=newest" class="<?= $activeNav === 'new' ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-body-md hover:text-on-surface' ?> transition-colors px-2 py-1">Novinky</a>
    <a href="catalog.php?deals=1" class="<?= $activeNav === 'deals' ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant font-body-md hover:text-on-surface' ?> transition-colors px-2 py-1">Slevy</a>
  </div>
  <div class="flex gap-md items-center">
    <form method="get" action="catalog.php" class="relative flex items-center group">
      <span class="material-symbols-outlined absolute left-3 text-on-surface-variant pointer-events-none group-focus-within:text-primary transition-colors">search</span>
      <input name="search" value="<?= h($searchValue) ?>" type="text" placeholder="Hledat produkty..." class="bg-surface-container border border-outline-variant rounded-full pl-10 pr-4 py-1.5 text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary w-48 lg:w-64 transition-all duration-200 placeholder:text-on-surface-variant/50">
    </form>
    <a href="cart.php" aria-label="Košík" class="text-primary hover:bg-surface-container-high p-2 rounded transition-all duration-200 relative">
      <span class="material-symbols-outlined text-[24px]">shopping_cart</span>
      <span class="nav-cart-badge absolute -top-0.5 -right-0.5 min-w-4 h-4 px-1 bg-primary text-on-primary text-[10px] font-bold rounded-full flex items-center justify-center" style="display:none">0</span>
    </a>
    <?php if ($currentUser): ?>
      <div class="flex items-center gap-sm">
        <?php if ($currentUser['avatar_url']): ?>
          <img src="<?= h($currentUser['avatar_url']) ?>" alt="" class="w-8 h-8 rounded-full object-cover border border-outline-variant">
        <?php else: ?>
          <span class="material-symbols-outlined text-[24px] text-on-surface-variant">account_circle</span>
        <?php endif; ?>
        <span class="font-body-md text-on-surface text-sm"><?= h($currentUser['full_name'] ?? $currentUser['nickname'] ?? $currentUser['email']) ?></span>
        <a href="logout.php" aria-label="Odhlásit se" class="text-on-surface-variant hover:text-error p-1 rounded transition-colors" title="Odhlásit se">
          <span class="material-symbols-outlined text-[20px]">logout</span>
        </a>
      </div>
    <?php else: ?>
      <button type="button" onclick="openLoginModal()" aria-label="Přihlášení" class="text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high p-2 rounded transition-all duration-200">
        <span class="material-symbols-outlined text-[24px]">account_circle</span>
      </button>
    <?php endif; ?>
  </div>
</nav>

<!-- Mobile TopBar -->
<nav class="md:hidden sticky top-0 z-50 flex justify-between items-center w-full px-margin py-base bg-background/90 backdrop-blur-md border-b border-outline-variant">
  <a href="index.php" class="font-display text-h2 font-extrabold text-primary tracking-tighter">VeVit Store</a>
  <div class="flex items-center gap-sm">
    <?php if ($currentUser): ?>
      <?php if ($currentUser['avatar_url']): ?>
        <img src="<?= h($currentUser['avatar_url']) ?>" alt="" class="w-8 h-8 rounded-full object-cover border border-outline-variant">
      <?php else: ?>
        <span class="material-symbols-outlined text-[24px] text-on-surface-variant">account_circle</span>
      <?php endif; ?>
    <?php else: ?>
      <button type="button" onclick="openLoginModal()" aria-label="Přihlášení" class="text-on-surface-variant hover:text-on-surface p-2 rounded transition-colors">
        <span class="material-symbols-outlined text-[24px]">account_circle</span>
      </button>
    <?php endif; ?>
    <a href="cart.php" aria-label="Košík" class="text-primary hover:bg-surface-container-high p-2 rounded relative">
      <span class="material-symbols-outlined text-[24px]">shopping_cart</span>
      <span class="nav-cart-badge absolute -top-0.5 -right-0.5 min-w-4 h-4 px-1 bg-primary text-on-primary text-[10px] font-bold rounded-full flex items-center justify-center" style="display:none">0</span>
    </a>
  </div>
</nav>

<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 z-[100] hidden" aria-modal="true" role="dialog">
  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeLoginModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4">
    <div class="relative bg-surface-container border border-outline-variant rounded-xl p-lg w-full max-w-sm shadow-2xl" onclick="event.stopPropagation()">
      <button type="button" onclick="closeLoginModal()" class="absolute top-3 right-3 text-on-surface-variant hover:text-error transition-colors" aria-label="Zavřít">
        <span class="material-symbols-outlined text-[24px]">close</span>
      </button>

      <div class="text-center mb-lg">
        <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center mx-auto mb-sm">
          <span class="material-symbols-outlined text-[24px] text-on-primary">login</span>
        </div>
        <h2 class="font-h2 text-h2 text-on-surface">Přihlášení</h2>
        <p class="font-caption text-caption text-on-surface-variant mt-xs">Přihlaste se svým VeVit účtem</p>
      </div>

      <div id="loginError" class="hidden bg-error/10 text-error font-body-md text-body-md px-md py-sm rounded-lg mb-md"></div>

      <form id="loginForm" class="flex flex-col gap-md" onsubmit="return submitLogin(event)">
        <div>
          <label class="block font-mono-label text-mono-label text-on-surface-variant mb-xs">E-mail</label>
          <input type="email" name="email" placeholder="vas@email.cz" required autofocus
            class="w-full bg-surface border border-outline-variant text-on-surface font-body-md px-md py-sm rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-on-surface-variant/50">
        </div>
        <div>
          <label class="block font-mono-label text-mono-label text-on-surface-variant mb-xs">Heslo</label>
          <input type="password" name="password" placeholder="••••••••" required
            class="w-full bg-surface border border-outline-variant text-on-surface font-body-md px-md py-sm rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-on-surface-variant/50">
        </div>
        <button type="submit"
          class="w-full bg-primary-container text-on-primary-container font-mono-label text-mono-label py-sm px-md rounded-lg border border-on-primary-container uppercase tracking-wider transition-all duration-200 shadow-[4px_4px_0px_0px_#10b981] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#10b981] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none">
          Přihlásit se
        </button>
      </form>

      <div class="mt-lg pt-lg border-t border-outline-variant text-center">
        <p class="font-caption text-caption text-on-surface-variant">
          Nemáte účet? <a href="https://vevit.cz/register" target="_blank" class="text-primary hover:underline">Registrujte se na VeVit</a>
        </p>
      </div>
    </div>
  </div>
</div>

<script>
function openLoginModal() {
  const m = document.getElementById('loginModal');
  m.classList.remove('hidden');
  document.body.style.overflow = 'hidden';
  const emailInput = m.querySelector('input[type="email"]');
  if (emailInput) setTimeout(() => emailInput.focus(), 50);
}
function closeLoginModal() {
  const m = document.getElementById('loginModal');
  m.classList.add('hidden');
  document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeLoginModal();
});
async function submitLogin(e) {
  e.preventDefault();
  const form = e.target;
  const email = form.email.value.trim();
  const password = form.password.value;
  const errorEl = document.getElementById('loginError');
  const btn = form.querySelector('button[type="submit"]');
  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="vevit-spinner"></span> Přihlašuji...';
  try {
    const res = await fetch('api/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    const data = await res.json();
    if (res.ok && data.success) {
      window.location.reload();
    } else {
      errorEl.textContent = data.error || 'Chyba při přihlášení.';
      errorEl.classList.remove('hidden');
      btn.disabled = false;
      btn.innerHTML = originalText;
    }
  } catch {
    errorEl.textContent = 'Chyba při komunikaci se serverem.';
    errorEl.classList.remove('hidden');
    btn.disabled = false;
    btn.innerHTML = originalText;
  }
  return false;
}
// Auto-open if URL has ?login=1
if (new URLSearchParams(window.location.search).get('login') === '1') {
  openLoginModal();
}
</script>
