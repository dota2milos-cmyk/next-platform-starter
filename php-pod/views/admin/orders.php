<div class="bg-white rounded-3 p-4">
    <h5 class="fw-bold mb-3">Sve Narudžbe (<?= count($orders) ?>)</h5>

    <?php if (!empty($orderDetail)): ?>
    <!-- Order Detail -->
    <div class="mb-4 p-4 border rounded-3" style="border-color:var(--border)!important">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold">Narudžba #<?= $orderDetail['id'] ?></h5>
            <a href="/admin/orders" class="btn btn-sm btn-outline-secondary">← Nazad</a>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <small class="text-muted d-block">Kupac</small>
                <strong><?= htmlspecialchars($orderDetail['shipping_name']) ?></strong><br>
                <small><?= htmlspecialchars($orderDetail['guest_email'] ?? '') ?></small>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Adresa</small>
                <?= htmlspecialchars($orderDetail['shipping_address']) ?>,<br>
                <?= htmlspecialchars($orderDetail['shipping_city']) ?> <?= htmlspecialchars($orderDetail['shipping_zip']) ?>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Status & Plaćanje</small>
                <span class="status-<?= $orderDetail['status'] ?>"><?= ucfirst($orderDetail['status']) ?></span><br>
                <small><?= $orderDetail['payment_method'] === 'bank_transfer' ? '🏦 Bankovni Transfer' : '💵 Pouzećem' ?></small>
            </div>
        </div>

        <table class="table table-sm">
            <thead><tr><th>Proizvod</th><th>Vel/Boja</th><th>Kol.</th><th>Cijena</th><th>Subtotal</th></tr></thead>
            <tbody>
                <?php foreach ($orderDetail['items'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><small><?= $item['size'] ?> <?= $item['color'] ?></small></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price'], 2) ?> KM</td>
                    <td><?= number_format($item['price'] * $item['quantity'], 2) ?> KM</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot><tr><th colspan="4">Ukupno</th><th class="fw-bold" style="color:var(--primary)"><?= number_format($orderDetail['total'], 2) ?> KM</th></tr></tfoot>
        </table>

        <form method="POST" action="/admin/orders/<?= $orderDetail['id'] ?>/status" class="d-flex gap-2 align-items-center mt-3">
            <label class="form-label mb-0 fw-semibold">Promijeni status:</label>
            <select name="status" class="form-select w-auto">
                <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                <option value="<?= $s ?>" <?= $orderDetail['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-primary btn-sm">Sačuvaj</button>
        </form>
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>#</th><th>Kupac</th><th>Grad</th><th>Iznos</th><th>Plaćanje</th><th>Status</th><th>Datum</th><th></th></tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td><strong>#<?= $o['id'] ?></strong></td>
                    <td><?= htmlspecialchars($o['user_name'] ?? $o['guest_email'] ?? 'Gost') ?></td>
                    <td class="text-muted"><?= htmlspecialchars($o['shipping_city'] ?? '-') ?></td>
                    <td class="fw-semibold"><?= number_format($o['total'], 2) ?> KM</td>
                    <td class="text-muted small"><?= $o['payment_method'] === 'bank_transfer' ? '🏦 Transfer' : '💵 COD' ?></td>
                    <td><span class="status-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                    <td class="text-muted small"><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
                    <td><a href="/admin/orders/<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary">Detalji</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
