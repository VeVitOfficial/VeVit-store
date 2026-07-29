<?php
require_once __DIR__ . '/config.php';
$pageTitle   = 'VeVit Store — Moderní e-shop s digitálními i fyzickými produkty';
$metaDesc    = 'VeVit Store nabízí digitální produkty ke stažení i fyzické zboží. Bezpečná platba přes Stripe, okamžité stažení, doprava zdarma nad 1 000 Kč.';
$activeNav   = 'home';
$searchValue = '';
include __DIR__ . '/lib/header.php';
?>

<main class="flex-1 w-full">

  <!-- ===== Hero Section ===== -->
  <section class="max-w-store mx-auto px-margin pt-12 pb-6 grid md:grid-cols-[18rem_1fr] gap-6">

    <!-- Sidebar: Category tree -->
    <aside id="subcatsSection" class="hidden md:block">
      <div class="bg-surface-container border border-outline-variant rounded-xl p-5 sticky top-20">
        <h2 class="font-mono-label text-mono-label text-on-surface-variant uppercase tracking-widest mb-3 flex items-center gap-2">
          <span class="material-symbols-outlined text-[16px] text-primary" aria-hidden="true">category</span>
          Kategorie
        </h2>
        <nav id="subcatsList" class="flex flex-col gap-0.5" aria-label="Kategorie produktů"></nav>
      </div>
    </aside>

    <!-- Main: Banner + categories + brands -->
    <div class="space-y-10 min-w-0">

      <!-- Hero Banner carousel -->
      <div id="bannerTrack" class="relative rounded-xl overflow-hidden border border-outline-variant h-[220px] md:h-[300px]" role="region" aria-label="Novinky a akce" aria-roledescription="karusel">
        <!-- Slide 1 — Novinky -->
        <div class="banner-slide absolute inset-0 flex items-center justify-between px-6 md:px-12 transition-opacity duration-500"
             data-slide="0" role="group" aria-label="Slide 1: Nová kolekce merch" aria-roledescription="slide"
             style="background:linear-gradient(120deg,#10b981 0%,#4edea3 55%,#6ffbbe 100%)">
          <div class="max-w-[65%] space-y-3">
            <span class="badge badge-neutral bg-black/15 border-black/20 text-on-primary-fixed">Novinka</span>
            <h2 class="font-display text-h2 md:text-h1 text-on-primary-fixed leading-tight">Nová kolekce merch</h2>
            <p class="font-body-md text-on-primary-fixed/90 hidden sm:block text-sm">Prémiová trička, mikiny a hrnky s logem VeVit.</p>
            <a href="catalog.php?sort=newest" class="btn btn-sm md:btn" style="background:#002113;color:#4edea3;border-color:#002113">
              Prohlédnout <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
            </a>
          </div>
          <span class="material-symbols-outlined text-[100px] md:text-[160px] text-on-primary-fixed/25 hidden sm:block" aria-hidden="true">shirt</span>
        </div>

        <!-- Slide 2 — Slevy -->
        <div class="banner-slide absolute inset-0 flex items-center justify-between px-6 md:px-12 transition-opacity duration-500 opacity-0"
             data-slide="1" role="group" aria-label="Slide 2: Slevy až 30 %" aria-roledescription="slide" aria-hidden="true"
             style="background:linear-gradient(120deg,#7c3aed 0%,#a855f7 55%,#c084fc 100%)">
          <div class="max-w-[65%] space-y-3">
            <span class="badge badge-neutral bg-black/15 border-black/20 text-white">Akce</span>
            <h2 class="font-display text-h2 md:text-h1 text-white leading-tight">Slevy až 30 %</h2>
            <p class="font-body-md text-white/90 hidden sm:block text-sm">Vybrané digitální nástroje a merch nyní slevněné.</p>
            <a href="catalog.php?deals=1" class="btn btn-sm md:btn" style="background:#0f1117;color:#c084fc;border-color:#0f1117">
              Zobrazit slevy <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
            </a>
          </div>
          <span class="material-symbols-outlined text-[100px] md:text-[160px] text-white/25 hidden sm:block" aria-hidden="true">sell</span>
        </div>

        <!-- Slide 3 — Digitální -->
        <div class="banner-slide absolute inset-0 flex items-center justify-between px-6 md:px-12 transition-opacity duration-500 opacity-0"
             data-slide="2" role="group" aria-label="Slide 3: Nástroje pro tvůrce" aria-roledescription="slide" aria-hidden="true"
             style="background:linear-gradient(120deg,#1d4ed8 0%,#0891b2 55%,#22d3ee 100%)">
          <div class="max-w-[65%] space-y-3">
            <span class="badge badge-neutral bg-black/15 border-black/20 text-white">Digitální</span>
            <h2 class="font-display text-h2 md:text-h1 text-white leading-tight">Nástroje pro tvůrce</h2>
            <p class="font-body-md text-white/90 hidden sm:block text-sm">UI kity a ikony pro Figma — okamžité stažení po platbě.</p>
            <a href="catalog.php?type=digital" class="btn btn-sm md:btn" style="background:#0f1117;color:#22d3ee;border-color:#0f1117">
              Stáhnout <span class="material-symbols-outlined text-[16px]" aria-hidden="true">download</span>
            </a>
          </div>
          <span class="material-symbols-outlined text-[100px] md:text-[160px] text-white/25 hidden sm:block" aria-hidden="true">download</span>
        </div>

        <!-- Carousel dots -->
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5" role="tablist" aria-label="Výběr slidu">
          <button class="banner-dot w-2 h-2 rounded-full bg-white/50 transition-all duration-200" data-go="0" role="tab" aria-label="Slide 1" aria-selected="true"></button>
          <button class="banner-dot w-2 h-2 rounded-full bg-white/50 transition-all duration-200" data-go="1" role="tab" aria-label="Slide 2" aria-selected="false"></button>
          <button class="banner-dot w-2 h-2 rounded-full bg-white/50 transition-all duration-200" data-go="2" role="tab" aria-label="Slide 3" aria-selected="false"></button>
        </div>
      </div>

      <!-- Parent categories grid -->
      <div id="categoriesSection" class="hidden">
        <div class="flex justify-between items-end mb-5">
          <h2 class="font-display text-h2 text-on-surface">Kategorie</h2>
          <a href="catalog.php" class="font-mono-label text-mono-label text-primary flex items-center gap-1 hover:gap-2 transition-all duration-150 uppercase">
            Vše <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
          </a>
        </div>
        <div id="categoriesGrid" class="grid grid-cols-1 sm:grid-cols-3 gap-4"></div>
      </div>

      <!-- Brands -->
      <div id="brandsSection" class="hidden">
        <div class="flex justify-between items-end mb-5">
          <h2 class="font-display text-h2 text-on-surface">Značky</h2>
          <a href="catalog.php" class="font-mono-label text-mono-label text-primary flex items-center gap-1 hover:gap-2 transition-all duration-150 uppercase">
            Vše <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
          </a>
        </div>
        <div id="brandsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"></div>
      </div>
    </div>
  </section>

  <!-- ===== Product rows ===== -->
  <div class="max-w-store mx-auto px-margin space-y-14 pb-16">

    <!-- Deals -->
    <section id="dealsSection" class="hidden" aria-labelledby="deals-heading">
      <div class="flex justify-between items-end mb-5">
        <h2 id="deals-heading" class="font-display text-h2 text-on-surface flex items-center gap-2">
          <span class="material-symbols-outlined text-primary icon-filled" aria-hidden="true">sell</span> V akci
        </h2>
        <a href="catalog.php?deals=1" class="font-mono-label text-mono-label text-primary flex items-center gap-1 hover:gap-2 transition-all duration-150 uppercase">
          Vše <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
        </a>
      </div>
      <div id="dealsGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4" role="list"></div>
    </section>

    <!-- VeVit products -->
    <section id="vevitSection" class="hidden" aria-labelledby="vevit-heading">
      <div class="flex justify-between items-end mb-5">
        <h2 id="vevit-heading" class="font-display text-h2 text-on-surface flex items-center gap-2">
          <span class="material-symbols-outlined text-primary icon-filled" aria-hidden="true">verified</span> Produkty VeVit
        </h2>
        <a href="catalog.php?brand=VeVit" class="font-mono-label text-mono-label text-primary flex items-center gap-1 hover:gap-2 transition-all duration-150 uppercase">
          Vše <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
        </a>
      </div>
      <div id="vevitGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4" role="list"></div>
    </section>

    <!-- Bestsellers -->
    <section id="bestSection" class="hidden" aria-labelledby="best-heading">
      <div class="flex justify-between items-end mb-5">
        <h2 id="best-heading" class="font-display text-h2 text-on-surface flex items-center gap-2">
          <span class="material-symbols-outlined text-primary icon-filled" aria-hidden="true">trending_up</span> Oblíbené
        </h2>
        <a href="catalog.php?sort=bestselling" class="font-mono-label text-mono-label text-primary flex items-center gap-1 hover:gap-2 transition-all duration-150 uppercase">
          Vše <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
        </a>
      </div>
      <div id="bestGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4" role="list"></div>
    </section>

    <!-- Recently viewed (logged in only) -->
    <section id="recentSection" class="hidden" aria-labelledby="recent-heading">
      <div class="flex justify-between items-end mb-5">
        <h2 id="recent-heading" class="font-display text-h2 text-on-surface flex items-center gap-2">
          <span class="material-symbols-outlined text-primary" aria-hidden="true">history</span> Naposledy prohlížené
        </h2>
      </div>
      <div id="recentGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4" role="list"></div>
    </section>

    <!-- ===== Value propositions ===== -->
    <section aria-labelledby="values-heading" class="py-8 border-t border-outline-variant">
      <h2 id="values-heading" class="sr-only">Proč nakupovat ve VeVit Store</h2>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-surface-container border border-outline-variant rounded-xl p-6">
          <div class="w-11 h-11 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-primary icon-filled text-[22px]" aria-hidden="true">local_shipping</span>
          </div>
          <h3 class="font-h2 text-[18px] font-bold text-on-surface mb-2">Doprava zdarma</h3>
          <!-- [PLACEHOLDER — ověřit aktuální podmínky dopravy před spuštěním] -->
          <p class="font-body-md text-sm text-on-surface-variant">U objednávek nad 1 000 Kč. Doručení do 2 pracovních dnů po ČR.</p>
        </div>
        <div class="bg-surface-container border border-outline-variant rounded-xl p-6">
          <div class="w-11 h-11 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-primary icon-filled text-[22px]" aria-hidden="true">bolt</span>
          </div>
          <h3 class="font-h2 text-[18px] font-bold text-on-surface mb-2">Okamžité stažení</h3>
          <p class="font-body-md text-sm text-on-surface-variant">Digitální produkty dostupné ke stažení ihned po potvrzení platby. Žádné čekání.</p>
        </div>
        <div class="bg-surface-container border border-outline-variant rounded-xl p-6">
          <div class="w-11 h-11 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-primary icon-filled text-[22px]" aria-hidden="true">lock</span>
          </div>
          <h3 class="font-h2 text-[18px] font-bold text-on-surface mb-2">Bezpečná platba</h3>
          <p class="font-body-md text-sm text-on-surface-variant">Platba kartou, Apple Pay i Google Pay přes zabezpečenou Stripe platformu.</p>
        </div>
      </div>
    </section>

    <!-- ===== About section ===== -->
    <section class="grid md:grid-cols-2 gap-10 items-center py-8 border-t border-outline-variant" aria-labelledby="about-heading">
      <div>
        <span class="font-mono-label text-mono-label text-primary uppercase tracking-widest block mb-3">O VeVit Store</span>
        <h2 id="about-heading" class="font-display text-h1 text-on-surface mb-4 leading-tight">Pečlivě vybraný sortiment pro každého</h2>
        <!-- [PLACEHOLDER — doplnit skutečný text o obchodě nebo hodnotách značky VeVit před spuštěním] -->
        <p class="font-body-md text-on-surface-variant mb-4 leading-relaxed">
          VeVit Store přináší digitální nástroje pro tvůrce a ověřené fyzické produkty. Každý produkt procházíme ručně, aby odpovídal standardům kvality, na které jste zvyklí.
        </p>
        <a href="about.php" class="btn btn-outline">
          Zjistit více <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
        </a>
      </div>
      <div class="flex flex-col gap-4">
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-primary text-[18px] icon-filled" aria-hidden="true">check_circle</span>
          </div>
          <div>
            <h3 class="font-body-md font-bold text-on-surface mb-1">Ověřené produkty</h3>
            <p class="font-body-md text-sm text-on-surface-variant">Každý produkt ručně procházíme před zařazením do katalogu.</p>
          </div>
        </div>
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-primary text-[18px] icon-filled" aria-hidden="true">support_agent</span>
          </div>
          <div>
            <h3 class="font-body-md font-bold text-on-surface mb-1">Zákaznická podpora</h3>
            <p class="font-body-md text-sm text-on-surface-variant">Odpovídáme na dotazy do 24 hodin v pracovní dny.</p>
          </div>
        </div>
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-primary text-[18px] icon-filled" aria-hidden="true">assignment_return</span>
          </div>
          <div>
            <h3 class="font-body-md font-bold text-on-surface mb-1">14 dní na vrácení</h3>
            <!-- [PLACEHOLDER — platí pouze pro fyzické produkty; ověřit s právním oddělením] -->
            <p class="font-body-md text-sm text-on-surface-variant">Fyzické zboží lze vrátit do 14 dnů bez udání důvodu.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== Newsletter / CTA ===== -->
    <section class="bg-surface-container border border-outline-variant rounded-2xl p-8 md:p-12 text-center" aria-labelledby="cta-heading">
      <span class="material-symbols-outlined text-[40px] text-primary icon-filled mb-4 block" aria-hidden="true">storefront</span>
      <h2 id="cta-heading" class="font-display text-h1 text-on-surface mb-3">Prozkoumat celý katalog</h2>
      <p class="font-body-md text-on-surface-variant max-w-md mx-auto mb-6">Stovky produktů. Nové přírůstky každý týden. Najdi, co hledáš.</p>
      <a href="catalog.php" class="btn btn-primary btn-lg">
        <span class="material-symbols-outlined text-[18px]" aria-hidden="true">explore</span>
        Otevřít katalog
      </a>
    </section>

  </div><!-- /max-w-store -->
</main>

<script>
/* ---- Escape helpers ---- */
function esc(s) {
  return String(s ?? '').replace(/[&<>"']/g, c => ({
    '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
  }[c]));
}
function fmtPrice(v) {
  return Number(v || 0).toLocaleString('cs-CZ', { maximumFractionDigits: 0 }).replace(/\s/g, ' ') + ' Kč';
}
function catBg(slug) {
  const map = {
    'elektronika-a-prislusenstvi': 'cat-bg-electronics',
    'umeni-papirenstvi-a-tvorivost': 'cat-bg-digital',
    'domov-kuchyne-a-bydleni': 'cat-bg-merch',
    'sport-fitness-a-outdoor': 'cat-bg-default',
    'moda-obleceni-a-doplnky': 'cat-bg-merch',
    'krasa-zdravi-a-osobni-pece': 'cat-bg-default',
    'chovatelske-potreby-pet-products': 'cat-bg-default',
    'eko-and-udrzitelny-zivotni-styl': 'cat-bg-default'
  };
  return map[slug] || 'cat-bg-default';
}
function hasSale(p) { return p.sale_price && Number(p.sale_price) > 0; }
function priceOf(p) { return hasSale(p) ? Number(p.sale_price) : Number(p.price); }
function isOutOfStock(p) { return p.type !== 'digital' && p.stock !== null && Number(p.stock) <= 0; }

function renderProductCard(p) {
  const digital  = p.type === 'digital';
  const soldout  = isOutOfStock(p);
  const sale     = hasSale(p);
  const price    = priceOf(p);
  const bg       = catBg(p.category_slug);
  const badge    = p.featured
    ? `<span class="badge badge-primary absolute top-2 left-2 z-10">Nové</span>`
    : sale
      ? `<span class="badge badge-danger absolute top-2 left-2 z-10">Sleva</span>`
      : '';
  const stockEl  = (!digital && p.stock !== null)
    ? `<span class="badge ${Number(p.stock) > 0 ? 'badge-success' : 'badge-danger'}">${Number(p.stock) > 0 ? 'Skladem' : 'Vyprodáno'}</span>`
    : digital
      ? `<span class="badge badge-primary">Digitální</span>`
      : '';

  const productPayload = JSON.stringify({
    id: Number(p.id), name: p.name, price: Number(p.price),
    sale_price: p.sale_price ? Number(p.sale_price) : null,
    type: p.type, slug: p.slug
  });
  const addBtnHtml = soldout
    ? `<button disabled aria-label="Vyprodáno" class="btn btn-icon btn-sm bg-surface border border-outline-variant text-on-surface-variant cursor-not-allowed opacity-50"><span class="material-symbols-outlined text-[18px]">block</span></button>`
    : `<button
        data-add-product="${productPayload.replace(/"/g, '&quot;')}"
        aria-label="Přidat ${esc(p.name)} do košíku"
        class="btn btn-icon btn-sm bg-surface border border-outline-variant hover:border-primary text-on-surface hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
      </button>`;

  const el = document.createElement('article');
  el.setAttribute('role', 'listitem');
  el.className = 'product-card bg-surface-container border border-outline-variant rounded-xl p-4 flex flex-col group relative';
  el.innerHTML = `
    ${badge}
    <a href="product.php?slug=${encodeURIComponent(p.slug)}"
       class="aspect-square ${bg} rounded-lg mb-4 flex items-center justify-center relative overflow-hidden border border-outline-variant/40 group-hover:scale-[1.02] transition-transform duration-300">
      <span class="font-mono-label text-mono-label text-white/60 uppercase tracking-widest text-center px-3 text-[11px]">${esc(p.category_name || 'VeVit')}</span>
    </a>
    <div class="flex items-center gap-1.5 flex-wrap mb-1.5">${stockEl}</div>
    <a href="product.php?slug=${encodeURIComponent(p.slug)}"
       class="font-h2 text-[16px] font-bold text-on-surface mb-1 line-clamp-1 hover:text-primary transition-colors duration-150">${esc(p.name)}</a>
    <p class="font-body-md text-[13px] text-on-surface-variant line-clamp-2 mb-3 flex-1 leading-relaxed">${esc(p.short_desc || '')}</p>
    <div class="flex justify-between items-center mt-auto pt-3 border-t border-outline-variant">
      <div class="flex flex-col gap-0.5">
        <span class="font-display text-[18px] font-bold text-primary">${fmtPrice(price)}</span>
        ${sale ? `<span class="font-caption text-caption text-on-surface-variant line-through">${fmtPrice(Number(p.price))}</span>` : ''}
      </div>
      ${addBtnHtml}
    </div>`;
  return el;
}

/* ---- Banner carousel ---- */
const bannerSlides = document.querySelectorAll('.banner-slide');
const bannerDots   = document.querySelectorAll('.banner-dot');
let bannerIndex = 0, bannerTimer = null;

function showBanner(i) {
  bannerIndex = (i + bannerSlides.length) % bannerSlides.length;
  bannerSlides.forEach((s, idx) => {
    s.style.opacity = idx === bannerIndex ? '1' : '0';
    s.setAttribute('aria-hidden', idx !== bannerIndex ? 'true' : 'false');
  });
  bannerDots.forEach((d, idx) => {
    const active = idx === bannerIndex;
    d.classList.toggle('bg-white', active);
    d.classList.toggle('w-5', active);
    d.classList.toggle('bg-white/50', !active);
    d.classList.toggle('w-2', !active);
    d.setAttribute('aria-selected', active ? 'true' : 'false');
  });
}
function restartBanner() {
  if (bannerTimer) clearInterval(bannerTimer);
  bannerTimer = setInterval(() => showBanner(bannerIndex + 1), 5000);
}
function startBanner() {
  if (!bannerSlides.length) return;
  showBanner(0);
  bannerDots.forEach(d => d.addEventListener('click', () => { showBanner(Number(d.dataset.go)); restartBanner(); }));
  restartBanner();
}

/* ---- Category sidebar ---- */
function renderSubcats(cats) {
  const parents = cats.filter(c => c.parent_id === null || c.parent_id === undefined);
  const l2 = cats.filter(c => c.parent_id !== null && c.parent_id !== undefined);
  if (!parents.length) return;
  const list = document.getElementById('subcatsList');
  list.innerHTML = '';

  parents.forEach(parent => {
    const wrap = document.createElement('div');
    wrap.className = 'flex flex-col gap-0.5';

    const head = document.createElement('a');
    head.href = `catalog.php?category=${encodeURIComponent(parent.slug)}`;
    head.className = 'flex items-center gap-2 px-2 py-1.5 rounded-md font-mono-label text-[11px] text-on-surface uppercase tracking-wider hover:text-primary hover:bg-primary/5 transition-colors font-semibold';
    head.innerHTML = `<span class="material-symbols-outlined text-[15px] text-primary">${esc(parent.icon || 'category')}</span>${esc(parent.name)}`;
    wrap.appendChild(head);

    l2.filter(c => Number(c.parent_id) === Number(parent.id)).forEach(k => {
      const a = document.createElement('a');
      a.href = `catalog.php?category=${encodeURIComponent(k.slug)}`;
      a.className = 'flex items-center justify-between pl-6 pr-2 py-1.5 rounded-md font-body-md text-[13px] text-on-surface-variant hover:text-primary hover:bg-primary/5 transition-colors';
      a.innerHTML = `<span class="truncate">${esc(k.name)}</span><span class="font-caption text-caption text-on-surface-variant/60 flex-shrink-0 ml-1">${Number(k.product_count)}</span>`;
      wrap.appendChild(a);
    });

    list.appendChild(wrap);
  });
  document.getElementById('subcatsSection').classList.remove('hidden');
}

/* ---- Parent categories grid ---- */
function renderCategories(cats) {
  const parents = cats.filter(c => c.parent_id === null || c.parent_id === undefined);
  if (!parents.length) return;
  const grid = document.getElementById('categoriesGrid');
  grid.innerHTML = '';
  parents.forEach((cat, i) => {
    const bg = catBg(cat.slug);
    const large = i === 0;
    const a = document.createElement('a');
    a.href = `catalog.php?category=${encodeURIComponent(cat.slug)}`;
    a.className = `${large ? 'sm:col-span-2' : ''} relative group rounded-xl overflow-hidden border border-outline-variant hover:border-primary/50 transition-colors block ${bg} h-[180px] sm:h-[200px]`;
    a.innerHTML = `
      <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
      <div class="absolute bottom-3 left-3 right-3">
        <h3 class="font-display ${large ? 'text-h2' : 'text-[18px]'} font-bold text-primary drop-shadow-sm">${esc(cat.name)}</h3>
        <p class="font-mono-label text-[11px] text-on-surface-variant mt-0.5 uppercase">${Number(cat.product_count)} produktů</p>
      </div>
      <div class="absolute top-2.5 right-2.5 bg-background/70 backdrop-blur-sm p-1.5 rounded-lg border border-outline-variant">
        <span class="material-symbols-outlined text-primary text-[18px]">${esc(cat.icon || 'category')}</span>
      </div>`;
    grid.appendChild(a);
  });
  document.getElementById('categoriesSection').classList.remove('hidden');
}

/* ---- Brands ---- */
function renderBrands(brands) {
  if (!brands.length) return;
  const grid = document.getElementById('brandsGrid');
  grid.innerHTML = '';
  brands.forEach(b => {
    const a = document.createElement('a');
    a.href = `catalog.php?brand=${encodeURIComponent(b.brand)}`;
    a.className = 'bg-surface-container border border-outline-variant rounded-xl p-5 flex flex-col items-center justify-center gap-2 hover:border-primary/50 hover:-translate-y-0.5 transition-all duration-200';
    a.innerHTML = `
      <span class="material-symbols-outlined text-[28px] text-primary icon-filled">sell</span>
      <span class="font-body-md font-bold text-on-surface text-[15px]">${esc(b.brand)}</span>
      <span class="font-mono-label text-[11px] text-on-surface-variant uppercase">${Number(b.product_count)} produktů</span>`;
    grid.appendChild(a);
  });
  document.getElementById('brandsSection').classList.remove('hidden');
}

/* ---- Product row fill ---- */
function fillRow(gridId, sectionId, products) {
  if (!products || !products.length) return;
  const grid = document.getElementById(gridId);
  grid.innerHTML = '';
  products.slice(0, 8).forEach(p => grid.appendChild(renderProductCard(p)));
  document.getElementById(sectionId).classList.remove('hidden');
}

/* ---- Auth hydration ---- */
async function hydrateNav() {
  try {
    const res = await fetch('api/me.php');
    const data = await res.json();
    if (data && data.user) {
      const user = data.user;
      const name = esc(user.full_name || user.nickname || user.email);
      const avatarHtml = user.avatar_url
        ? `<img src="${esc(user.avatar_url)}" alt="" class="w-8 h-8 rounded-full object-cover border border-outline-variant">`
        : `<span class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">person</span></span>`;
      const markup = `<div class="flex items-center gap-2">${avatarHtml}<span class="font-body-md text-sm text-on-surface max-w-[120px] truncate">${name}</span><a href="logout.php" aria-label="Odhlásit se" class="p-1.5 rounded-md text-on-surface-variant hover:text-error transition-colors"><span class="material-symbols-outlined text-[18px]">logout</span></a></div>`;
      document.getElementById('navAuth')?.insertAdjacentHTML('beforeend', markup);
      const navAuth = document.getElementById('navAuth');
      const loginBtn = navAuth?.querySelector('button');
      if (loginBtn) loginBtn.remove();
    }
  } catch { /* guest — keep login button */ }
}

/* ---- Homepage load ---- */
async function loadHome() {
  try {
    const [dealsRes, vevitRes, bestRes, recentRes, catRes, brandRes] = await Promise.all([
      fetch('api/products.php?deals=1&per_page=8').then(r => r.json()).catch(() => ({})),
      fetch('api/products.php?brand=VeVit&per_page=8').then(r => r.json()).catch(() => ({})),
      fetch('api/products.php?sort=bestselling&per_page=8').then(r => r.json()).catch(() => ({})),
      fetch('api/recent.php').then(r => r.json()).catch(() => ({})),
      fetch('api/categories.php').then(r => r.json()).catch(() => ({})),
      fetch('api/brands.php').then(r => r.json()).catch(() => ({}))
    ]);
    if (dealsRes.products)  fillRow('dealsGrid',  'dealsSection',  dealsRes.products);
    if (vevitRes.products)  fillRow('vevitGrid',  'vevitSection',  vevitRes.products);
    if (bestRes.products)   fillRow('bestGrid',   'bestSection',   bestRes.products);
    if (recentRes.products) fillRow('recentGrid', 'recentSection', recentRes.products);
    if (catRes.categories)  { renderSubcats(catRes.categories); renderCategories(catRes.categories); }
    if (brandRes.brands)    renderBrands(brandRes.brands);
  } catch (err) {
    console.error('Homepage load failed:', err);
  }
}

/* ---- Event delegation for add-to-cart buttons ---- */
document.addEventListener('click', function(e) {
  const btn = e.target.closest('[data-add-product]');
  if (!btn) return;
  try {
    const product = JSON.parse(btn.dataset.addProduct);
    Cart.add(product);
  } catch { /* malformed data — silently ignore */ }
});

startBanner();
hydrateNav();
loadHome();
</script>

<?php include __DIR__ . '/lib/footer.php'; ?>
