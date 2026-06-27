<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <div class="bg-white border rounded-3 p-3 mb-3" style="border-color:var(--border)!important">
                <h6 class="fw-bold mb-3">🏷️ Kategorije</h6>
                <a href="/products" class="category-card mb-2 py-2 px-3 d-flex align-items-center gap-2 text-decoration-none <?= empty($activeCategory) ? 'active' : '' ?>">
                    <span>🛍️</span> <span>Sve Kategorije</span>
                </a>
                <?php foreach ($categories as $cat): ?>
                <a href="/products?category=<?= $cat['slug'] ?><?= $search ? '&search='.urlencode($search) : '' ?>"
                   class="category-card mb-2 py-2 px-3 d-flex align-items-center gap-2 text-decoration-none <?= $activeCategory === $cat['slug'] ? 'active' : '' ?>">
                    <span><?= $cat['icon'] ?></span>
                    <span><?= htmlspecialchars($cat['name']) ?></span>
                    <span class="ms-auto badge bg-secondary"><?= $cat['product_count'] ?></span>
                </a>
                <?php endforeach; ?>
            </div>

            <div class="bg-white border rounded-3 p-3" style="border-color:var(--border)!important">
                <h6 class="fw-bold mb-3">💡 Šta je POD?</h6>
                <p class="small text-muted mb-0">Print on Demand — naručuješ samo ono što ti treba. Nema minimuma, nema zaliha. Savršeno za mala preduzeća i kreativce.</p>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">
                        <?php if ($activeCategory): ?>
                            <?php foreach ($categories as $c) if ($c['slug'] === $activeCategory) echo $c['icon'] . ' ' . htmlspecialchars($c['name']); ?>
                        <?php elseif ($search): ?>
                            🔍 Rezultati za "<?= htmlspecialchars($search) ?>"
                        <?php else: ?>
                            🛍️ Svi Proizvodi
                        <?php endif; ?>
                    </h4>
                    <small class="text-muted"><?= count($products) ?> proizvoda pronađeno</small>
                </div>
                <form class="d-flex gap-2" method="GET">
                    <?php if ($activeCategory): ?><input type="hidden" name="category" value="<?= htmlspecialchars($activeCategory) ?>"><?php endif; ?>
                    <input type="text" name="search" class="form-control" placeholder="Pretraži..." value="<?= htmlspecialchars($search ?? '') ?>">
                    <button class="btn btn-primary px-3">🔍</button>
                </form>
            </div>

            <?php if (empty($products)): ?>
            <div class="text-center py-5">
                <div style="font-size:4rem">😔</div>
                <h5 class="fw-bold">Nema pronađenih proizvoda</h5>
                <a href="/products" class="btn btn-primary mt-2">Prikaži sve</a>
            </div>
            <?php else: ?>
            <div class="row g-3">
                <?php foreach ($products as $product): ?>
                <div class="col-6 col-lg-4">
                    <?php include __DIR__ . '/partials/product-card.php'; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
