<?php $emojis = ['👕','🏠','📚','🎒']; $emoji = $emojis[($product['category_id']-1) % 4]; ?>
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Početna</a></li>
            <li class="breadcrumb-item"><a href="/products" class="text-decoration-none">Proizvodi</a></li>
            <li class="breadcrumb-item"><a href="/products?category=<?= $product['category_slug'] ?>" class="text-decoration-none"><?= htmlspecialchars($product['category_name']) ?></a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Image -->
        <div class="col-md-5">
            <div class="product-detail-img"><?= $emoji ?></div>
            <?php if ($product['allow_custom_design']): ?>
            <div class="mt-3 p-3 bg-light rounded-3 text-center">
                <span class="fw-semibold">✨ Custom Design dostupan</span><br>
                <small class="text-muted">Uploadajte vlastiti dizajn pri narudžbi</small>
            </div>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="col-md-7">
            <span class="badge mb-2" style="background:var(--primary)"><?= htmlspecialchars($product['category_name']) ?></span>
            <h1 class="fw-bold" style="font-size:2rem"><?= htmlspecialchars($product['name']) ?></h1>

            <div class="d-flex align-items-center gap-3 my-3">
                <div class="stars" style="font-size:1.1rem">
                    <?= str_repeat('★', round($product['avg_rating'])) ?><?= str_repeat('☆', 5 - round($product['avg_rating'])) ?>
                </div>
                <span class="text-muted"><?= number_format($product['avg_rating'], 1) ?> (<?= $product['review_count'] ?> recenzija)</span>
            </div>

            <div class="price mb-4" style="font-size:2.5rem"><?= number_format($product['base_price'], 2) ?> <small style="font-size:1rem">KM</small></div>

            <p class="text-muted"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

            <form class="add-to-cart-form" method="POST" action="/cart/add" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" id="selectedSize" name="size" value="">
                <input type="hidden" id="selectedColor" name="color" value="">

                <?php if (!empty($product['sizes'])): ?>
                <div class="mb-4">
                    <label class="form-label">Veličina</label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($product['sizes'] as $i => $size): ?>
                        <button type="button" class="size-btn <?= $i === 0 ? 'active' : '' ?>" data-size="<?= htmlspecialchars($size) ?>"
                            onclick="document.getElementById('selectedSize').value='<?= htmlspecialchars($size) ?>'">
                            <?= htmlspecialchars($size) ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($product['colors'])): ?>
                <div class="mb-4">
                    <label class="form-label">Boja: <span id="colorLabel" class="text-muted"><?= htmlspecialchars($product['colors'][0] ?? '') ?></span></label>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <?php
                        $colorMap = ['Bijela'=>'#ffffff','Crna'=>'#222222','Siva'=>'#9e9e9e','Navy'=>'#1a237e','Bordo'=>'#880e4f','Zelena'=>'#2e7d32','Plava'=>'#1565c0','Crvena'=>'#c62828','Prirodna'=>'#d7ccc8'];
                        foreach ($product['colors'] as $i => $color):
                            $hex = $colorMap[$color] ?? '#888';
                        ?>
                        <button type="button" class="color-btn <?= $i === 0 ? 'active' : '' ?>"
                            data-color="<?= htmlspecialchars($color) ?>"
                            style="background:<?= $hex ?>; border-color:<?= $hex ?>"
                            title="<?= htmlspecialchars($color) ?>"
                            onclick="document.getElementById('selectedColor').value='<?= htmlspecialchars($color) ?>'; document.getElementById('colorLabel').textContent='<?= htmlspecialchars($color) ?>'">
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($product['allow_custom_design']): ?>
                <div class="mb-4">
                    <label class="form-label">🎨 Vaš Dizajn (opciono)</label>
                    <div class="design-upload-area" id="designUploadArea">
                        <div style="font-size:2.5rem">☁️</div>
                        <p class="fw-semibold mb-1">Kliknite ili prevucite sliku</p>
                        <small class="text-muted">PNG, JPG, SVG — max 10MB, min 300 DPI preporučeno</small>
                        <input type="file" id="designFile" name="custom_design" accept="image/*" style="display:none">
                        <img id="designPreview" class="design-preview mt-3" style="display:none" alt="Preview">
                    </div>
                </div>
                <?php endif; ?>

                <div class="row g-2 mb-3">
                    <div class="col-3">
                        <label class="form-label">Količina</label>
                        <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?>" class="form-control">
                    </div>
                    <div class="col-9 d-flex align-items-end">
                        <button type="submit" class="btn-add py-2 fs-5">
                            🛒 Dodaj u Košaricu
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-3 p-3 rounded-3" style="background:var(--light)">
                <div class="d-flex gap-4">
                    <div class="text-center"><div style="font-size:1.5rem">🚚</div><small>Dostava 48-72h</small></div>
                    <div class="text-center"><div style="font-size:1.5rem">↩️</div><small>30 dana povrat</small></div>
                    <div class="text-center"><div style="font-size:1.5rem">🔒</div><small>Sigurna naplata</small></div>
                    <div class="text-center"><div style="font-size:1.5rem">🏆</div><small>Garantirano</small></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews -->
    <div class="mt-5">
        <h3 class="fw-bold mb-4">💬 Recenzije Kupaca (<?= $product['review_count'] ?>)</h3>
        <div class="row g-4">
            <div class="col-md-8">
                <?php if (empty($reviews)): ?>
                <p class="text-muted">Nema još recenzija. Budite prvi!</p>
                <?php endif; ?>
                <?php foreach ($reviews as $review): ?>
                <div class="bg-white border rounded-3 p-4 mb-3" style="border-color:var(--border)!important">
                    <div class="d-flex justify-content-between">
                        <strong><?= htmlspecialchars($review['user_name']) ?></strong>
                        <small class="text-muted"><?= date('d.m.Y', strtotime($review['created_at'])) ?></small>
                    </div>
                    <div class="stars my-1"><?= str_repeat('★', $review['rating']) ?><?= str_repeat('☆', 5 - $review['rating']) ?></div>
                    <p class="mb-0"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if (Auth::user()): ?>
            <div class="col-md-4">
                <div class="bg-white border rounded-3 p-4" style="border-color:var(--border)!important">
                    <h5 class="fw-bold">Ostavite Recenziju</h5>
                    <form method="POST" action="/product/<?= $product['id'] ?>/review">
                        <div class="mb-3">
                            <label class="form-label">Ocjena</label>
                            <input type="hidden" id="ratingValue" name="rating" value="5">
                            <div class="d-flex gap-1" style="font-size:1.8rem; cursor:pointer">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star-rate text-warning" data-value="<?= $i ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea name="comment" class="form-control" rows="3" placeholder="Vaše iskustvo..."></textarea>
                        </div>
                        <button class="btn btn-primary w-100">Pošalji Recenziju</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
