<?php
require_once __DIR__ . '/config.php';
$pageTitle   = 'VeVit Store — Moderní e-shop s digitálními i fyzickými produkty';
$metaDesc    = 'VeVit Store nabízí digitální produkty ke stažení i fyzické zboží. Bezpečná platba přes Stripe, okamžité stažení.';
$activeNav   = 'home';
$searchValue = '';
include __DIR__ . '/lib/header.php';
?>

<main class="flex-1 w-full">

  <!-- ===== Hero ===== -->
  <section class="relative overflow-hidden" aria-labelledby="hero-heading"
           style="background:linear-gradient(135deg,#052e16 0%,#064e3b 45%,#0f172a 100%)">
    <!-- Subtle glow -->
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
         style="background:radial-gradient(ellipse 70% 60% at 65% 50%,rgba(16,185,129,.13) 0%,transparent 70%)"></div>
    <!-- Faint grid pattern -->
    <div class="absolute inset-0 pointer-events-none opacity-[0.035]" aria-hidden="true"
         style="background-image:linear-gradient(rgba(255,255,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.5) 1px,transparent 1px);background-size:48px 48px"></div>

    <div class="relative max-w-store mx-auto px-margin py-16 md:py-24 grid md:grid-cols-2 gap-10 md:gap-16 items-center">

      <!-- Left: text -->
      <div>
        <span class="inline-flex items-center gap-1.5 font-mono-label text-mono-label text-primary uppercase tracking-widest mb-4">
          <span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>
          VeVit Store
        </span>
        <h1 id="hero-heading" class="font-display text-[36px] md:text-[52px] font-extrabold text-on-surface leading-[1.1] tracking-tight mb-5"
            style="color:#f0fdf4">
          Nakupuj chytře.<br>
          <span style="color:#4edea3">Digitálně i fyzicky.</span>
        </h1>
        <p class="font-body-lg text-[17px] leading-relaxed mb-8" style="color:#a7f3d0">
          Pečlivě vybraný sortiment — od digitálních nástrojů pro tvůrce po ověřené fyzické produkty. Bezpečná platba, okamžité stažení.
        </p>

        <div class="flex flex-wrap gap-3 mb-10">
          <a href="catalog.php" class="btn btn-primary btn-lg">
            <span class="material-symbols-outlined text-[18px]" aria-hidden="true">explore</span>
            Prohlédnout produkty
          </a>
          <a href="catalog.php#categories" class="btn btn-outline btn-lg" style="border-color:rgba(78,222,163,.4);color:#4edea3">
            <span class="material-symbols-outlined text-[18px]" aria-hidden="true">category</span>
            Zobrazit kategorie
          </a>
        </div>

        <!-- Benefits -->
        <div class="flex flex-col gap-2.5">
          <?php
          $heroBenefits = [
            ['lock',          'Bezpečná platba přes Stripe'],
            ['bolt',          'Digitální produkty ihned po platbě'],
            ['assignment_return', 'Fyzické zboží — 14 dní na vrácení'],
          ];
          foreach ($heroBenefits as [$icon, $text]):
          ?>
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-[18px] icon-filled flex-shrink-0" style="color:#4edea3" aria-hidden="true"><?= $icon ?></span>
            <span class="font-body-md text-[14px]" style="color:#a7f3d0"><?= $text ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Right: brand composition -->
      <div class="hidden md:flex items-center justify-center" aria-hidden="true">
        <div class="relative w-full max-w-[380px] aspect-square">
          <!-- Background circle glow -->
          <div class="absolute inset-0 rounded-full opacity-20"
               style="background:radial-gradient(circle,#10b981 0%,transparent 70%)"></div>

          <!-- Floating product cards -->
          <div class="absolute top-[8%] left-[5%] bg-surface-container border border-outline-variant rounded-xl p-4 shadow-lg w-40"
               style="border-color:rgba(16,185,129,.2)">
            <div class="w-full aspect-square rounded-lg cat-bg-digital flex items-center justify-center mb-3">
              <span class="material-symbols-outlined text-[36px] text-white/70">palette</span>
            </div>
            <div class="font-body-md text-[12px] font-bold text-on-surface truncate">UI Kit Pro</div>
            <div class="font-display text-[15px] font-bold" style="color:#4edea3">490 Kč</div>
          </div>

          <div class="absolute top-[5%] right-[5%] bg-surface-container border border-outline-variant rounded-xl p-4 shadow-lg w-36"
               style="border-color:rgba(16,185,129,.2)">
            <div class="w-full aspect-square rounded-lg cat-bg-electronics flex items-center justify-center mb-3">
              <span class="material-symbols-outlined text-[32px] text-white/70">devices</span>
            </div>
            <div class="font-body-md text-[11px] font-bold text-on-surface truncate">Sluchátka TWS</div>
            <div class="font-display text-[14px] font-bold" style="color:#4edea3">1 290 Kč</div>
          </div>

          <div class="absolute bottom-[12%] right-[0%] bg-surface-container border border-outline-variant rounded-xl p-4 shadow-lg w-44"
               style="border-color:rgba(16,185,129,.2)">
            <div class="w-full aspect-square rounded-lg cat-bg-merch flex items-center justify-center mb-3">
              <span class="material-symbols-outlined text-[36px] text-white/70">shirt</span>
            </div>
            <div class="font-body-md text-[12px] font-bold text-on-surface truncate">VeVit Mikina</div>
            <div class="font-display text-[15px] font-bold" style="color:#4edea3">890 Kč</div>
          </div>

          <div class="absolute bottom-[8%] left-[5%] bg-primary-container/20 border border-primary/30 rounded-xl px-4 py-3 shadow-lg">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px] icon-filled text-primary">verified</span>
              <span class="font-mono-label text-[11px] text-on-surface uppercase tracking-wide">Ověřeno</span>
            </div>
          </div>

          <!-- Center logo -->
          <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 rounded-2xl bg-surface-container border-2 flex items-center justify-center shadow-xl"
               style="border-color:#10b981">
            <img src="images/logo_notext.png" alt="VeVit" width="48" height="48" class="w-12 h-12 object-contain rounded-lg">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== Categories ===== -->
  <section id="categoriesSection" class="max-w-store mx-auto px-margin py-14" aria-labelledby="cats-heading">
    <div class="flex items-end justify-between mb-6">
      <div>
        <span class="font-mono-label text-mono-label text-primary uppercase tracking-widest block mb-1">Sortiment</span>
        <h2 id="cats-heading" class="font-display text-h1 text-on-surface">Nakupuj podle kategorií</h2>
      </div>
      <a href="catalog.php" class="btn btn-outline btn-sm hidden sm:inline-flex">
        Celý katalog <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
      </a>
    </div>
    <div id="categoriesGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
      <!-- Skeleton placeholders shown while JS loads -->
      <?php for ($i = 0; $i < 8; $i++): ?>
      <div class="skeleton rounded-xl h-[160px]"></div>
      <?php endfor; ?>
    </div>
    <!-- Empty state (shown by JS if no categories) -->
    <div id="catsEmpty" class="hidden text-center py-12">
      <span class="material-symbols-outlined text-[48px] text-on-surface-variant/40 block mb-3" aria-hidden="true">category</span>
      <p class="font-body-md text-on-surface-variant mb-4">Kategorie se načítají nebo nejsou k dispozici.</p>
      <a href="catalog.php" class="btn btn-primary">Otevřít katalog</a>
    </div>
  </section>

  <!-- ===== Doporučené produkty ===== -->
  <section id="featuredSection" class="bg-surface-container-low border-y border-outline-variant" aria-labelledby="featured-heading">
    <div class="max-w-store mx-auto px-margin py-14">
      <div class="flex items-end justify-between mb-6">
        <div>
          <span class="font-mono-label text-mono-label text-primary uppercase tracking-widest block mb-1">Doporučujeme</span>
          <h2 id="featured-heading" class="font-display text-h1 text-on-surface">Vybrané produkty</h2>
        </div>
        <a href="catalog.php" class="btn btn-outline btn-sm hidden sm:inline-flex">
          Vše <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
        </a>
      </div>
      <div id="featuredGrid" class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php for ($i = 0; $i < 4; $i++): ?>
        <div class="skeleton rounded-xl h-[280px]"></div>
        <?php endfor; ?>
      </div>
      <div id="featuredEmpty" class="hidden">
        <div class="bg-surface-container border border-outline-variant rounded-2xl p-10 text-center">
          <span class="material-symbols-outlined text-[40px] text-primary/50 block mb-3" aria-hidden="true">storefront</span>
          <h3 class="font-h2 text-h2 text-on-surface mb-2">Produkty se připravují</h3>
          <p class="font-body-md text-on-surface-variant mb-5 max-w-sm mx-auto">
            Katalog bude brzy naplněn. Prozatím si prohlédni strukturu obchodu nebo se ozvi.
          </p>
          <div class="flex flex-wrap gap-3 justify-center">
            <a href="catalog.php" class="btn btn-primary">Přejít do katalogu</a>
            <a href="contact.php" class="btn btn-outline">Kontakt</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== V akci (deals) ===== -->
  <section id="dealsSection" class="hidden max-w-store mx-auto px-margin py-14" aria-labelledby="deals-heading">
    <div class="flex items-end justify-between mb-6">
      <h2 id="deals-heading" class="font-display text-h1 text-on-surface flex items-center gap-2">
        <span class="material-symbols-outlined text-primary icon-filled" aria-hidden="true">sell</span> V akci
      </h2>
      <a href="catalog.php?deals=1" class="btn btn-outline btn-sm hidden sm:inline-flex">
        Vše <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
      </a>
    </div>
    <div id="dealsGrid" class="grid grid-cols-2 md:grid-cols-4 gap-4" role="list"></div>
  </section>

  <!-- ===== Digital / Physical rozcestník ===== -->
  <section class="max-w-store mx-auto px-margin py-14" aria-labelledby="types-heading">
    <span class="font-mono-label text-mono-label text-primary uppercase tracking-widest block mb-1">Co nabízíme</span>
    <h2 id="types-heading" class="font-display text-h1 text-on-surface mb-8">Dva světy, jeden obchod</h2>
    <div class="grid md:grid-cols-2 gap-6">

      <!-- Digital -->
      <a href="catalog.php?type=digital"
         class="group relative overflow-hidden bg-surface-container border border-outline-variant rounded-2xl p-8 hover:border-primary/50 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg block">
        <div class="absolute top-0 right-0 w-48 h-48 rounded-full opacity-5 -translate-y-1/2 translate-x-1/4"
             style="background:radial-gradient(circle,#10b981,transparent)" aria-hidden="true"></div>
        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5">
          <span class="material-symbols-outlined text-[26px] text-primary icon-filled" aria-hidden="true">download</span>
        </div>
        <h3 class="font-display text-h2 text-on-surface mb-2">Digitální produkty</h3>
        <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-5">
          UI kity, ikony, šablony a nástroje pro tvůrce. Dostupné ke stažení ihned po zaplacení. Žádná doprava, žádné čekání.
        </p>
        <span class="inline-flex items-center gap-1.5 font-mono-label text-mono-label text-primary uppercase text-[11px] group-hover:gap-2.5 transition-all duration-150">
          Procházet digitální
          <span class="material-symbols-outlined text-[14px]" aria-hidden="true">arrow_forward</span>
        </span>
      </a>

      <!-- Physical -->
      <a href="catalog.php?type=physical"
         class="group relative overflow-hidden bg-surface-container border border-outline-variant rounded-2xl p-8 hover:border-primary/50 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg block">
        <div class="absolute top-0 right-0 w-48 h-48 rounded-full opacity-5 -translate-y-1/2 translate-x-1/4"
             style="background:radial-gradient(circle,#10b981,transparent)" aria-hidden="true"></div>
        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5">
          <span class="material-symbols-outlined text-[26px] text-primary icon-filled" aria-hidden="true">inventory_2</span>
        </div>
        <h3 class="font-display text-h2 text-on-surface mb-2">Fyzické produkty</h3>
        <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-5">
          Elektronika, móda, domácnost, sport a péče o sebe. Ověřený sortiment s doručením.
        </p>
        <span class="inline-flex items-center gap-1.5 font-mono-label text-mono-label text-primary uppercase text-[11px] group-hover:gap-2.5 transition-all duration-150">
          Procházet fyzické
          <span class="material-symbols-outlined text-[14px]" aria-hidden="true">arrow_forward</span>
        </span>
      </a>
    </div>
  </section>

  <!-- ===== Oblíbené (bestsellers) ===== -->
  <section id="bestSection" class="hidden bg-surface-container-low border-y border-outline-variant" aria-labelledby="best-heading">
    <div class="max-w-store mx-auto px-margin py-14">
      <div class="flex items-end justify-between mb-6">
        <h2 id="best-heading" class="font-display text-h1 text-on-surface flex items-center gap-2">
          <span class="material-symbols-outlined text-primary icon-filled" aria-hidden="true">trending_up</span> Oblíbené
        </h2>
        <a href="catalog.php?sort=bestselling" class="btn btn-outline btn-sm hidden sm:inline-flex">
          Vše <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
        </a>
      </div>
      <div id="bestGrid" class="grid grid-cols-2 md:grid-cols-4 gap-4" role="list"></div>
    </div>
  </section>

  <!-- ===== Value propositions ===== -->
  <section class="max-w-store mx-auto px-margin py-14" aria-labelledby="values-heading">
    <h2 id="values-heading" class="sr-only">Proč nakupovat ve VeVit Store</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

      <div class="bg-surface-container border border-outline-variant rounded-2xl p-7 flex flex-col">
        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5 flex-shrink-0">
          <span class="material-symbols-outlined text-primary icon-filled text-[26px]" aria-hidden="true">bolt</span>
        </div>
        <h3 class="font-display text-[20px] font-bold text-on-surface mb-2">Okamžité stažení</h3>
        <p class="font-body-md text-sm text-on-surface-variant leading-relaxed flex-1">
          Digitální produkty jsou dostupné ihned po potvrzení platby. Stahuj opakovaně po dobu platnosti linku.
        </p>
      </div>

      <div class="bg-surface-container border border-outline-variant rounded-2xl p-7 flex flex-col">
        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5 flex-shrink-0">
          <span class="material-symbols-outlined text-primary icon-filled text-[26px]" aria-hidden="true">lock</span>
        </div>
        <h3 class="font-display text-[20px] font-bold text-on-surface mb-2">Bezpečná platba</h3>
        <p class="font-body-md text-sm text-on-surface-variant leading-relaxed flex-1">
          Platba kartou přes zabezpečenou platformu Stripe. Citlivé údaje se nikdy nedostanou na naše servery.
        </p>
      </div>

      <div class="bg-surface-container border border-outline-variant rounded-2xl p-7 flex flex-col">
        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5 flex-shrink-0">
          <span class="material-symbols-outlined text-primary icon-filled text-[26px]" aria-hidden="true">assignment_return</span>
        </div>
        <h3 class="font-display text-[20px] font-bold text-on-surface mb-2">Vrácení bez problémů</h3>
        <p class="font-body-md text-sm text-on-surface-variant leading-relaxed flex-1">
          Fyzické zboží vrátíš do 14 dnů bez udání důvodu. Zákaznická podpora odpoví do 24 hodin v pracovní dny.
        </p>
      </div>
    </div>
  </section>

  <!-- ===== O VeVit ===== -->
  <section class="bg-surface-container-low border-y border-outline-variant" aria-labelledby="about-heading">
    <div class="max-w-store mx-auto px-margin py-14 grid md:grid-cols-2 gap-12 items-center">
      <div>
        <span class="font-mono-label text-mono-label text-primary uppercase tracking-widest block mb-3">O obchodě</span>
        <h2 id="about-heading" class="font-display text-h1 text-on-surface mb-4 leading-tight">Pečlivě vybraný sortiment</h2>
        <p class="font-body-md text-on-surface-variant leading-relaxed mb-6">
          VeVit Store kombinuje digitální nástroje pro moderní tvůrce a ověřené fyzické produkty pro každodenní život. Každý produkt prochází ruční kontrolou před zařazením do katalogu.
        </p>
        <a href="about.php" class="btn btn-outline">
          Zjistit více <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
        </a>
      </div>
      <div class="flex flex-col gap-5">
        <?php
        $abouts = [
          ['check_circle',      'Ověřené produkty',     'Každý produkt ručně procházíme před zařazením do katalogu.'],
          ['support_agent',     'Zákaznická podpora',   'Odpovídáme na dotazy do 24 hodin v pracovní dny.'],
          ['assignment_return', '14 dní na vrácení',    'Fyzické zboží vrátíš do 14 dnů bez udání důvodu.'],
        ];
        foreach ($abouts as [$icon, $title, $desc]):
        ?>
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-primary text-[18px] icon-filled" aria-hidden="true"><?= $icon ?></span>
          </div>
          <div>
            <h3 class="font-body-md font-bold text-on-surface mb-0.5"><?= $title ?></h3>
            <p class="font-body-md text-sm text-on-surface-variant"><?= $desc ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ===== Final CTA ===== -->
  <section class="max-w-store mx-auto px-margin py-14" aria-labelledby="cta-heading">
    <div class="relative overflow-hidden rounded-3xl p-10 md:p-14 text-center"
         style="background:linear-gradient(135deg,#052e16 0%,#064e3b 60%,#0f172a 100%)">
      <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
           style="background:radial-gradient(ellipse 60% 80% at 50% 50%,rgba(16,185,129,.1) 0%,transparent 70%)"></div>
      <div class="relative">
        <span class="font-mono-label text-mono-label uppercase tracking-widest block mb-3" style="color:#4edea3">Připraven nakoupit?</span>
        <h2 id="cta-heading" class="font-display text-[32px] md:text-[40px] font-extrabold mb-3 leading-tight" style="color:#f0fdf4">
          Prozkoumat celý katalog
        </h2>
        <p class="font-body-md leading-relaxed mb-8 max-w-lg mx-auto" style="color:#a7f3d0">
          Stovky produktů. Nové přírůstky průběžně. Najdi, co hledáš — digitálně i fyzicky.
        </p>
        <div class="flex flex-wrap gap-3 justify-center">
          <a href="catalog.php" class="btn btn-primary btn-lg">
            <span class="material-symbols-outlined text-[18px]" aria-hidden="true">explore</span>
            Otevřít katalog
          </a>
          <a href="contact.php" class="btn btn-lg" style="border:1px solid rgba(78,222,163,.35);color:#4edea3;background:transparent">
            <span class="material-symbols-outlined text-[18px]" aria-hidden="true">mail</span>
            Kontakt
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Naposledy prohlížené (jen přihlášení) -->
  <section id="recentSection" class="hidden max-w-store mx-auto px-margin pb-14" aria-labelledby="recent-heading">
    <div class="flex items-end justify-between mb-6">
      <h2 id="recent-heading" class="font-display text-h1 text-on-surface flex items-center gap-2">
        <span class="material-symbols-outlined text-primary" aria-hidden="true">history</span> Naposledy prohlížené
      </h2>
    </div>
    <div id="recentGrid" class="grid grid-cols-2 md:grid-cols-4 gap-4" role="list"></div>
  </section>

</main>

<script>
/* ---- Helpers ---- */
function esc(s) {
  return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}
function fmtPrice(v) {
  return Number(v || 0).toLocaleString('cs-CZ', { maximumFractionDigits: 0 }).replace(/\s/g, ' ') + ' Kč';
}
function catBg(slug) {
  return ({'elektronika-a-prislusenstvi':'cat-bg-electronics','umeni-papirenstvi-a-tvorivost':'cat-bg-digital','domov-kuchyne-a-bydleni':'cat-bg-merch','sport-fitness-a-outdoor':'cat-bg-default','moda-obleceni-a-doplnky':'cat-bg-merch','krasa-zdravi-a-osobni-pece':'cat-bg-default','chovatelske-potreby-pet-products':'cat-bg-default','eko-and-udrzitelny-zivotni-styl':'cat-bg-default'})[slug] || 'cat-bg-default';
}
function hasSale(p) { return p.sale_price && Number(p.sale_price) > 0; }
function priceOf(p) { return hasSale(p) ? Number(p.sale_price) : Number(p.price); }
function isOutOfStock(p) { return p.type !== 'digital' && p.stock !== null && Number(p.stock) <= 0; }

/* ---- Product card ---- */
function renderProductCard(p) {
  const digital  = p.type === 'digital';
  const soldout  = isOutOfStock(p);
  const sale     = hasSale(p);
  const price    = priceOf(p);
  const bg       = catBg(p.category_slug);
  const badge    = p.featured
    ? `<span class="badge badge-primary absolute top-2 left-2 z-10">Nové</span>`
    : sale ? `<span class="badge badge-danger absolute top-2 left-2 z-10">Sleva</span>` : '';
  const stockEl  = (!digital && p.stock !== null)
    ? `<span class="badge ${Number(p.stock) > 0 ? 'badge-success' : 'badge-danger'}">${Number(p.stock) > 0 ? 'Skladem' : 'Vyprodáno'}</span>`
    : digital ? `<span class="badge badge-primary">Digitální</span>` : '';
  const payload  = JSON.stringify({id:Number(p.id),name:p.name,price:Number(p.price),sale_price:p.sale_price?Number(p.sale_price):null,type:p.type,slug:p.slug});
  const addBtn   = soldout
    ? `<button disabled aria-label="Vyprodáno" class="btn btn-icon btn-sm bg-surface border border-outline-variant text-on-surface-variant cursor-not-allowed opacity-50"><span class="material-symbols-outlined text-[18px]">block</span></button>`
    : `<button data-add-product="${payload.replace(/"/g,'&quot;')}" aria-label="Přidat ${esc(p.name)} do košíku" class="btn btn-icon btn-sm bg-surface border border-outline-variant hover:border-primary text-on-surface hover:text-primary transition-colors"><span class="material-symbols-outlined text-[18px]">add_shopping_cart</span></button>`;

  const el = document.createElement('article');
  el.setAttribute('role', 'listitem');
  el.className = 'product-card bg-surface-container border border-outline-variant rounded-xl p-4 flex flex-col group relative';
  el.innerHTML = `
    ${badge}
    <a href="product.php?slug=${encodeURIComponent(p.slug)}" class="aspect-square ${bg} rounded-lg mb-4 flex items-center justify-center relative overflow-hidden border border-outline-variant/40 group-hover:scale-[1.02] transition-transform duration-300" tabindex="-1">
      <span class="font-mono-label text-mono-label text-white/60 uppercase tracking-widest text-center px-3 text-[10px]">${esc(p.category_name || 'VeVit')}</span>
    </a>
    <div class="flex items-center gap-1.5 flex-wrap mb-1.5">${stockEl}</div>
    <a href="product.php?slug=${encodeURIComponent(p.slug)}" class="font-h2 text-[15px] font-bold text-on-surface mb-1 line-clamp-2 hover:text-primary transition-colors duration-150 leading-snug">${esc(p.name)}</a>
    <p class="font-body-md text-[12px] text-on-surface-variant line-clamp-2 mb-3 flex-1 leading-relaxed">${esc(p.short_desc || '')}</p>
    <div class="flex justify-between items-center mt-auto pt-3 border-t border-outline-variant">
      <div>
        <span class="font-display text-[17px] font-bold text-primary block">${fmtPrice(price)}</span>
        ${sale ? `<span class="font-caption text-caption text-on-surface-variant line-through">${fmtPrice(Number(p.price))}</span>` : ''}
      </div>
      ${addBtn}
    </div>`;
  return el;
}

/* ---- Category card ---- */
function renderCatCard(cat, large) {
  const bg = catBg(cat.slug);
  const a  = document.createElement('a');
  a.href   = `catalog.php?category=${encodeURIComponent(cat.slug)}`;
  a.className = `${large ? 'sm:col-span-2' : ''} relative group rounded-xl overflow-hidden border border-outline-variant hover:border-primary/50 transition-colors block ${bg} h-[160px]`;
  a.innerHTML = `
    <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-transparent"></div>
    <div class="absolute bottom-3 left-3 right-10">
      <h3 class="font-display text-[15px] font-bold text-white leading-tight mb-0.5 line-clamp-2">${esc(cat.name)}</h3>
      <p class="font-mono-label text-[10px] text-white/60 uppercase">${Number(cat.product_count)} produktů</p>
    </div>
    <div class="absolute top-2 right-2 bg-black/40 backdrop-blur-sm p-1.5 rounded-lg border border-white/10">
      <span class="material-symbols-outlined text-primary text-[16px]">${esc(cat.icon || 'category')}</span>
    </div>`;
  return a;
}

/* ---- Fill product row ---- */
function fillRow(gridId, sectionId, products, showEmpty) {
  const section = document.getElementById(sectionId);
  const grid    = document.getElementById(gridId);
  if (!section || !grid) return;
  if (!products || !products.length) {
    if (showEmpty) section.classList.remove('hidden');
    return;
  }
  grid.innerHTML = '';
  products.slice(0, 8).forEach(p => grid.appendChild(renderProductCard(p)));
  section.classList.remove('hidden');
}

/* ---- Render categories ---- */
function renderCategories(cats) {
  const grid  = document.getElementById('categoriesGrid');
  const empty = document.getElementById('catsEmpty');
  if (!grid) return;
  const parents = cats.filter(c => c.parent_id === null || c.parent_id === undefined);
  grid.innerHTML = '';
  if (!parents.length) {
    empty && empty.classList.remove('hidden');
    return;
  }
  // Show max 8 parent cats
  parents.slice(0, 8).forEach((cat, i) => grid.appendChild(renderCatCard(cat, i === 0 && parents.length > 3)));
  document.getElementById('categoriesSection')?.classList.remove('hidden');
}

/* ---- Homepage load ---- */
async function loadHome() {
  try {
    const [featRes, dealsRes, bestRes, recentRes, catRes] = await Promise.all([
      fetch('api/products.php?sort=featured&per_page=8').then(r => r.json()).catch(() => ({})),
      fetch('api/products.php?deals=1&per_page=8').then(r => r.json()).catch(() => ({})),
      fetch('api/products.php?sort=bestselling&per_page=8').then(r => r.json()).catch(() => ({})),
      fetch('api/recent.php').then(r => r.json()).catch(() => ({})),
      fetch('api/categories.php').then(r => r.json()).catch(() => ({})),
    ]);

    // Featured: show section with empty state if no products
    const featGrid    = document.getElementById('featuredGrid');
    const featEmpty   = document.getElementById('featuredEmpty');
    if (featGrid) featGrid.innerHTML = '';
    if (featRes.products && featRes.products.length) {
      featRes.products.slice(0, 8).forEach(p => featGrid?.appendChild(renderProductCard(p)));
      featEmpty?.classList.add('hidden');
    } else {
      featEmpty?.classList.remove('hidden');
    }

    if (dealsRes.products && dealsRes.products.length)  fillRow('dealsGrid',  'dealsSection',  dealsRes.products, false);
    if (bestRes.products  && bestRes.products.length)   fillRow('bestGrid',   'bestSection',   bestRes.products,  false);
    if (recentRes.products && recentRes.products.length) fillRow('recentGrid', 'recentSection', recentRes.products, false);
    if (catRes.categories) renderCategories(catRes.categories);
    else {
      document.getElementById('categoriesGrid').innerHTML = '';
      document.getElementById('catsEmpty')?.classList.remove('hidden');
    }

  } catch (err) {
    console.error('Homepage load error:', err);
    // Show empty states on failure
    document.getElementById('featuredGrid') && (document.getElementById('featuredGrid').innerHTML = '');
    document.getElementById('featuredEmpty')?.classList.remove('hidden');
    document.getElementById('categoriesGrid').innerHTML = '';
    document.getElementById('catsEmpty')?.classList.remove('hidden');
  }
}

/* ---- Add-to-cart delegation ---- */
document.addEventListener('click', function(e) {
  const btn = e.target.closest('[data-add-product]');
  if (!btn) return;
  try { Cart.add(JSON.parse(btn.dataset.addProduct)); } catch { /* malformed */ }
});

loadHome();
</script>

<?php include __DIR__ . '/lib/footer.php'; ?>
