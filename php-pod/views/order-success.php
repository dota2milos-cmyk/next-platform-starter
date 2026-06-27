<div class="container py-5 text-center">
    <div style="max-width:600px; margin:0 auto">
        <div style="font-size:5rem">🎉</div>
        <h2 class="fw-bold mt-3">Narudžba Potvrđena!</h2>
        <p class="text-muted fs-5">Hvala na narudžbi! Vaša narudžba <strong>#<?= $order['id'] ?></strong> je primljena i u obradi je.</p>

        <div class="bg-white border rounded-3 p-4 mt-4 mb-4 text-start" style="border-color:var(--border)!important">
            <h5 class="fw-bold mb-3">📦 Detalji Narudžbe</h5>
            <div class="row g-3">
                <div class="col-6">
                    <small class="text-muted">Narudžba broj</small>
                    <div class="fw-bold">#<?= $order['id'] ?></div>
                </div>
                <div class="col-6">
                    <small class="text-muted">Status</small>
                    <div><span class="status-pending">Na čekanju</span></div>
                </div>
                <div class="col-6">
                    <small class="text-muted">Ukupno</small>
                    <div class="fw-bold" style="color:var(--primary)"><?= number_format($order['total'], 2) ?> KM</div>
                </div>
                <div class="col-6">
                    <small class="text-muted">Plaćanje</small>
                    <div class="fw-semibold">
                        <?= $order['payment_method'] === 'bank_transfer' ? '🏦 Bankovni Transfer' : '💵 Pouzećem' ?>
                    </div>
                </div>
                <div class="col-12">
                    <small class="text-muted">Dostava na adresu</small>
                    <div><?= htmlspecialchars($order['shipping_name']) ?>,
                        <?= htmlspecialchars($order['shipping_address']) ?>,
                        <?= htmlspecialchars($order['shipping_city']) ?>
                    </div>
                </div>
            </div>

            <?php if ($order['payment_method'] === 'bank_transfer'): ?>
            <div class="mt-4 p-3 rounded-3" style="background:var(--light)">
                <h6 class="fw-bold">🏦 Podaci za Uplatu</h6>
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted">Banka:</td><td><strong>UniCredit Bank BH</strong></td></tr>
                    <tr><td class="text-muted">IBAN:</td><td><strong>BA391020000123456789</strong></td></tr>
                    <tr><td class="text-muted">Iznos:</td><td><strong><?= number_format($order['total'], 2) ?> KM</strong></td></tr>
                    <tr><td class="text-muted">Poziv na broj:</td><td><strong>#<?= $order['id'] ?></strong></td></tr>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <div class="d-flex gap-3 justify-content-center">
            <a href="/orders" class="btn btn-primary">📋 Moje Narudžbe</a>
            <a href="/products" class="btn btn-outline-primary">🛍️ Nastavi Kupovinu</a>
        </div>

        <p class="text-muted small mt-4">
            Potvrda narudžbe poslana je na email: <strong><?= htmlspecialchars($order['guest_email'] ?? Auth::user()['email'] ?? '') ?></strong>
        </p>
    </div>
</div>
