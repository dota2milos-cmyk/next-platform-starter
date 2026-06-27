<div class="container py-5">
    <h2 class="fw-bold mb-4">🛒 Moja Košarica</h2>

    <?php if (empty($cartItems)): ?>
    <div class="text-center py-5">
        <div style="font-size:5rem">🛒</div>
        <h4 class="fw-bold mt-3">Košarica je prazna</h4>
        <p class="text-muted">Dodajte neke proizvode da biste nastavili.</p>
        <a href="/products" class="btn btn-primary mt-2">Pregledaj Proizvode</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <?php foreach ($cartItems as $key => $item): ?>
            <?php $emojis = ['👕','🏠','📚','🎒']; $emoji = $emojis[($item['product']['category_id']-1) % 4]; ?>
            <div class="cart-item d-flex gap-3 align-items-start">
                <div class="item-img"><?= $emoji ?></div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($item['product']['name']) ?></h6>
                            <div class="text-muted small">
                                <?= $item['size'] ? '📏 ' . htmlspecialchars($item['size']) : '' ?>
                                <?= $item['color'] ? ' &nbsp; 🎨 ' . htmlspecialchars($item['color']) : '' ?>
                                <?= $item['custom_design'] ? ' &nbsp; ✨ Custom design' : '' ?>
                            </div>
                        </div>
                        <button onclick="removeItem('<?= htmlspecialchars($key) ?>')" class="btn btn-sm btn-outline-danger">✕</button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="d-flex align-items-center gap-2">
                            <button onclick="updateQty('<?= htmlspecialchars($key) ?>', -1)" class="btn btn-sm btn-outline-secondary px-2">−</button>
                            <input type="number" id="qty_<?= htmlspecialchars($key) ?>" value="<?= $item['qty'] ?>" min="1" class="form-control form-control-sm text-center" style="width:60px">
                            <button onclick="updateQty('<?= htmlspecialchars($key) ?>', 1)" class="btn btn-sm btn-outline-secondary px-2">+</button>
                        </div>
                        <strong class="text-primary fs-5"><?= number_format($item['subtotal'], 2) ?> KM</strong>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="d-flex gap-3 mt-4">
                <a href="/products" class="btn btn-outline-primary">← Nastavi Kupovinu</a>
                <form method="POST" action="/cart/clear">
                    <button class="btn btn-outline-danger">🗑️ Isprazni Košaricu</button>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bg-white border rounded-3 p-4 sticky-top" style="top:80px; border-color:var(--border)!important">
                <h5 class="fw-bold mb-3">📋 Pregled Narudžbe</h5>
                <?php foreach ($cartItems as $item): ?>
                <div class="d-flex justify-content-between small mb-2">
                    <span><?= htmlspecialchars($item['product']['name']) ?> ×<?= $item['qty'] ?></span>
                    <span><?= number_format($item['subtotal'], 2) ?> KM</span>
                </div>
                <?php endforeach; ?>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Dostava:</span>
                    <span class="text-success fw-semibold">Besplatno 🎉</span>
                </div>
                <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                    <span>Ukupno:</span>
                    <span style="color:var(--primary)"><?= number_format($total, 2) ?> KM</span>
                </div>
                <a href="/checkout" class="btn btn-primary w-100 py-3 fw-bold fs-5">
                    Nastavi na Plaćanje →
                </a>
                <div class="text-center mt-3">
                    <small class="text-muted">🔒 Sigurna naplata · SSL zaštita</small>
                </div>

                <!-- Promo code -->
                <div class="mt-3">
                    <label class="form-label small fw-semibold">Promo Kod</label>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm" placeholder="POD2025">
                        <button class="btn btn-outline-secondary btn-sm">Primijeni</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
