<div class="container py-5">
    <h2 class="fw-bold mb-4">📋 Moje Narudžbe</h2>

    <?php if (empty($orders)): ?>
    <div class="text-center py-5">
        <div style="font-size:4rem">📦</div>
        <h5 class="fw-bold mt-3">Nemate narudžbi</h5>
        <p class="text-muted">Počnite kupovinu sada!</p>
        <a href="/products" class="btn btn-primary">Pregledaj Proizvode</a>
    </div>
    <?php else: ?>
    <div class="row g-3">
        <?php foreach ($orders as $order): ?>
        <div class="col-12">
            <div class="bg-white border rounded-3 p-4" style="border-color:var(--border)!important">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-1">Narudžba #<?= $order['id'] ?></h6>
                        <small class="text-muted"><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></small>
                    </div>
                    <div class="text-end">
                        <span class="status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
                        <div class="fw-bold mt-1" style="color:var(--primary)"><?= number_format($order['total'], 2) ?> KM</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                    <div class="text-muted small">
                        📍 <?= htmlspecialchars($order['shipping_city']) ?>, <?= htmlspecialchars($order['shipping_country']) ?><br>
                        💰 <?= $order['payment_method'] === 'bank_transfer' ? 'Bankovni Transfer' : 'Pouzećem' ?>
                    </div>
                    <a href="/order/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">Detalji →</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
