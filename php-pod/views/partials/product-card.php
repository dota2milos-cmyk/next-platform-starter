<?php $emojis = ['👕','🏠','📚','🎒']; $emoji = $emojis[($product['category_id']-1) % 4]; ?>
<div class="product-card fade-in">
    <a href="/product/<?= $product['id'] ?>" class="text-decoration-none text-dark">
        <div class="product-img">
            <?= $emoji ?>
            <?php if ($product['allow_custom_design']): ?>
            <span class="badge-custom">Custom Design</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <small class="text-muted"><?= htmlspecialchars($product['category_name']) ?></small>
            <h5 class="mt-1"><?= htmlspecialchars($product['name']) ?></h5>
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="stars"><?= str_repeat('★', round($product['avg_rating'])) ?><?= str_repeat('☆', 5 - round($product['avg_rating'])) ?></div>
                <small class="text-muted">(<?= $product['review_count'] ?>)</small>
            </div>
            <div class="price"><?= number_format($product['base_price'], 2) ?> KM</div>
        </div>
    </a>
    <div class="px-3 pb-3">
        <a href="/product/<?= $product['id'] ?>" class="btn-add">Pogledaj Detalje</a>
    </div>
</div>
