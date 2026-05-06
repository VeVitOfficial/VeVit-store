<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Košík — VeVit Store';
$activeNav = 'cart';
include __DIR__ . '/lib/header.php';
?>

<main class="flex-1 w-full max-w-[1200px] mx-auto px-margin py-xl flex flex-col lg:flex-row gap-gutter">

  <!-- Cart Items -->
  <section class="flex-grow lg:w-2/3 flex flex-col gap-md">
    <div class="flex flex-col gap-xs mb-md">
      <span class="font-mono-label text-mono-label text-primary uppercase tracking-widest">Krok 1 / 3</span>
      <h1 class="font-display text-display text-on-surface">Váš košík</h1>
    </div>

    <div id="cartItems" class="flex flex-col gap-md"></div>

    <div id="emptyCart" class="hidden text-center py-xl flex flex-col items-center gap-md">
      <div class="w-20 h-20 rounded-full bg-surface-container border border-outline-variant flex items-center justify-center">
        <span class="material-symbols-outlined text-[40px] text-on-surface-variant">shopping_bag</span>
      </div>
      <h2 class="font-h2 text-h2 text-on-surface">Košík je prázdný</h2>
      <p class="font-body-md text-on-surface-variant max-w-md">Vyrazte do katalogu a najděte si něco pěkného.</p>
      <a href="catalog.php" class="bg-primary-container text-on-primary-fixed font-mono-label text-mono-label py-sm px-md rounded-DEFAULT border-2 border-on-primary-fixed neo-shadow uppercase inline-flex items-center gap-xs">
        <span class="material-symbols-outlined">storefront</span> Pokračovat v nákupu
      </a>
    </div>

    <div id="cartContinue" class="hidden">
      <a href="catalog.php" class="inline-flex items-center gap-sm text-on-surface-variant hover:text-primary transition-colors font-mono-label text-mono-label uppercase">
        <span class="material-symbols-outlined">arrow_back</span> Pokračovat v nákupu
      </a>
    </div>
  </section>

  <!-- Summary -->
  <aside class="w-full lg:w-1/3 flex flex-col gap-md">
    <div class="bg-surface-container border border-outline-variant rounded-xl p-md sticky top-32" id="cartSummary">
      <h2 class="font-h2 text-h2 text-on-surface mb-md pb-sm border-b border-outline-variant">Souhrn</h2>
      <div class="flex flex-col gap-sm font-body-md text-body-md mb-md">
        <div class="flex justify-between text-on-surface-variant"><span>Mezisoučet</span><span id="summarySubtotal" class="text-on-surface">0 Kč</span></div>
        <div class="flex justify-between text-on-surface-variant"><span>Doprava</span><span id="summaryShipping" class="text-on-surface">Zdarma</span></div>
        <div id="summaryShippingNote" class="font-caption text-caption text-primary hidden">Doprava zdarma od 1 000 Kč</div>
      </div>
      <div class="flex justify-between items-center border-t border-outline-variant pt-md mb-md">
        <span class="font-h2 text-h2 text-on-surface">Celkem</span>
        <span id="summaryTotal" class="font-display text-h1 text-primary">0 Kč</span>
      </div>
      <button onclick="goToCheckout()" class="w-full bg-primary-container text-on-primary-fixed font-mono-label text-mono-label py-4 border-2 border-on-primary-fixed rounded uppercase tracking-wider flex items-center justify-center gap-sm hard-shadow-primary hard-shadow-primary-active transition-all">
        K pokladně <span class="material-symbols-outlined">arrow_forward</span>
      </button>
      <div class="mt-md flex items-center justify-center gap-xs text-on-surface-variant font-caption text-caption">
        <span class="material-symbols-outlined" style="font-size: 16px;">lock</span> Zabezpečená platba přes Stripe
      </div>
    </div>

    <!-- Trust badges -->
    <div class="bg-surface-container border border-outline-variant rounded-xl p-md flex items-center justify-around">
      <div class="text-center flex flex-col items-center gap-xs">
        <span class="material-symbols-outlined text-primary">verified_user</span>
        <span class="font-mono-label text-caption text-on-surface-variant uppercase">SSL</span>
      </div>
      <div class="text-center flex flex-col items-center gap-xs">
        <span class="material-symbols-outlined text-primary">credit_card</span>
        <span class="font-mono-label text-caption text-on-surface-variant uppercase">Stripe</span>
      </div>
      <div class="text-center flex flex-col items-center gap-xs">
        <span class="material-symbols-outlined text-primary">undo</span>
        <span class="font-mono-label text-caption text-on-surface-variant uppercase">14 dní</span>
      </div>
    </div>
  </aside>
</main>

<script>
function goToCheckout() {
  if (!Cart.get().length) { window.showToast && window.showToast('Košík je prázdný', 'error'); return; }
  window.location.href = 'checkout.php';
}
function esc(t) { const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }

const __catBg = {
  merch: 'cat-bg-merch',
  'digitalni-produkty': 'cat-bg-digital',
  elektronika: 'cat-bg-electronics'
};

function renderCart() {
  const items = Cart.get();
  const itemsEl = document.getElementById('cartItems');
  const emptyEl = document.getElementById('emptyCart');
  const summaryEl = document.getElementById('cartSummary');
  const continueEl = document.getElementById('cartContinue');

  if (!items.length) {
    itemsEl.innerHTML = '';
    emptyEl.classList.remove('hidden');
    if (summaryEl) summaryEl.style.opacity = '0.4';
    if (continueEl) continueEl.classList.add('hidden');
  } else {
    emptyEl.classList.add('hidden');
    if (summaryEl) summaryEl.style.opacity = '1';
    if (continueEl) continueEl.classList.remove('hidden');
  }

  itemsEl.innerHTML = items.map(item => {
    const slug = item.type === 'digital' ? 'digitalni-produkty' : 'merch';
    const bg = __catBg[slug] || 'cat-bg-default';
    const lineTotal = (Number(item.price) * Number(item.qty)).toLocaleString('cs-CZ');
    return `
      <article class="flex flex-col sm:flex-row gap-md p-md bg-surface-container border border-outline-variant rounded-xl">
        <a href="product.php?slug=${esc(item.slug)}" class="w-full sm:w-32 h-32 ${bg} rounded border border-outline-variant flex items-center justify-center shrink-0 overflow-hidden">
          <span class="material-symbols-outlined text-white/30 text-[40px]">image</span>
        </a>
        <div class="flex flex-col flex-grow gap-sm">
          <div class="flex justify-between items-start gap-sm">
            <div>
              <span class="font-mono-label text-caption text-primary uppercase">${item.type === 'digital' ? 'Digitální' : 'Fyzický'}</span>
              <h2 class="font-h2 text-h2 text-on-surface mt-xs">${esc(item.name)}</h2>
              <p class="font-body-md text-body-md text-on-surface-variant mt-xs">${Number(item.price).toLocaleString('cs-CZ')} Kč / ks</p>
            </div>
            <button onclick="Cart.remove(${item.id})" aria-label="Odstranit" class="text-on-surface-variant hover:text-error transition-colors p-1">
              <span class="material-symbols-outlined">delete</span>
            </button>
          </div>
          <div class="flex justify-between items-end mt-auto">
            <div class="flex items-center bg-surface border border-outline-variant rounded h-10">
              <button onclick="Cart.updateQuantity(${item.id}, ${item.qty - 1})" aria-label="Méně" class="w-10 h-full flex items-center justify-center text-on-surface hover:text-primary transition-colors border-r border-outline-variant"><span class="material-symbols-outlined">remove</span></button>
              <span class="font-mono-label text-mono-label w-12 text-center">${item.qty}</span>
              <button onclick="Cart.updateQuantity(${item.id}, ${item.qty + 1})" aria-label="Více" class="w-10 h-full flex items-center justify-center text-on-surface hover:text-primary transition-colors border-l border-outline-variant"><span class="material-symbols-outlined">add</span></button>
            </div>
            <div class="font-h2 text-h2 text-on-surface">${lineTotal} Kč</div>
          </div>
        </div>
      </article>`;
  }).join('');

  const subtotal = Cart.total();
  const shipping = Cart.shipping();
  const total = subtotal + shipping;
  document.getElementById('summarySubtotal').textContent = subtotal.toLocaleString('cs-CZ') + ' Kč';
  document.getElementById('summaryShipping').textContent = shipping === 0 ? 'Zdarma' : shipping.toLocaleString('cs-CZ') + ' Kč';
  document.getElementById('summaryTotal').textContent = total.toLocaleString('cs-CZ') + ' Kč';

  const noteEl = document.getElementById('summaryShippingNote');
  if (Cart.hasPhysical() && subtotal > 0 && subtotal < 1000) {
    noteEl.textContent = 'Přidej za ' + (1000 - subtotal).toLocaleString('cs-CZ') + ' Kč pro dopravu zdarma';
    noteEl.classList.remove('hidden');
  } else {
    noteEl.classList.add('hidden');
  }
}

window.addEventListener('cartchange', renderCart);
document.addEventListener('DOMContentLoaded', renderCart);
</script>

<?php include __DIR__ . '/lib/footer.php'; ?>
