<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1>Dizajnirajte.<br><span>Naručite.</span><br>Isporučimo.</h1>
                <p class="my-4">Vaša Print on Demand platforma za majice, kućni dekor, knjige, foto albume i još mnogo toga. Uploadajte dizajn — mi štampamo i šaljemo.</p>
                <a href="/products" class="btn-hero me-3">Pregledaj Proizvode</a>
                <a href="/register" class="btn btn-outline-light btn-lg rounded-pill">Besplatna Registracija</a>
            </div>
            <div class="col-lg-6 text-center mt-5 mt-lg-0">
                <div style="font-size:8rem; line-height:1; filter:drop-shadow(0 20px 40px rgba(0,0,0,0.3))">
                    🎨
                </div>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <?php foreach (['👕','🏠','📚','🎒'] as $e): ?>
                    <div style="background:rgba(255,255,255,0.15); border-radius:12px; width:60px; height:60px; display:flex; align-items:center; justify-content:center; font-size:1.8rem"><?= $e ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats bar -->
<div style="background:var(--primary); padding:20px 0; color:white">
    <div class="container">
        <div class="row text-center">
            <div class="col-3"><strong style="font-size:1.5rem">500+</strong><br><small>Proizvoda</small></div>
            <div class="col-3"><strong style="font-size:1.5rem">2.500+</strong><br><small>Zadovoljnih kupaca</small></div>
            <div class="col-3"><strong style="font-size:1.5rem">48h</strong><br><small>Isporuka</small></div>
            <div class="col-3"><strong style="font-size:1.5rem">100%</strong><br><small>Garantirano</small></div>
        </div>
    </div>
</div>

<!-- Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4 text-center">Kategorije Proizvoda</h2>
        <div class="row g-3">
            <?php foreach ($categories as $cat): ?>
            <div class="col-6 col-md-3">
                <a href="/products?category=<?= $cat['slug'] ?>" class="category-card">
                    <div class="icon"><?= $cat['icon'] ?></div>
                    <h5><?= htmlspecialchars($cat['name']) ?></h5>
                    <small><?= $cat['product_count'] ?> proizvoda</small>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-4" style="background:#f0ebff">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">🔥 Popularni Proizvodi</h2>
            <a href="/products" class="btn btn-outline-primary">Svi Proizvodi →</a>
        </div>
        <div class="row g-3">
            <?php foreach ($featured as $product): ?>
            <div class="col-6 col-md-3">
                <?php include __DIR__ . '/partials/product-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold text-center mb-5">Kako Funkcionira?</h2>
        <div class="row g-4 text-center">
            <?php
            $steps = [
                ['🔍', '1. Odaberite Proizvod', 'Pregledajte naš katalog od 500+ POD proizvoda u svim kategorijama.'],
                ['🎨', '2. Uploadajte Dizajn', 'Dodajte svoju sliku, logo ili tekst. Mi prilagođavamo format.'],
                ['🛒', '3. Naručite', 'Sigurna naplata. Prihvaćamo bankovni transfer i pouzećem.'],
                ['📦', '4. Isporuka', 'Štampamo i šaljemo direktno na vašu adresu za 48-72h.'],
            ];
            foreach ($steps as $step): ?>
            <div class="col-md-3">
                <div style="font-size:3rem; margin-bottom:15px"><?= $step[0] ?></div>
                <h5 class="fw-bold"><?= $step[1] ?></h5>
                <p class="text-muted small"><?= $step[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section style="background:linear-gradient(135deg, var(--dark), var(--primary)); color:white; padding:60px 0">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Spreman za vlastiti POD biznis?</h2>
        <p class="mb-4 opacity-75">Registrujte se besplatno i počnite prodavati svoje dizajne već danas.</p>
        <a href="/register" class="btn-hero">Počni Besplatno 🚀</a>
    </div>
</section>
