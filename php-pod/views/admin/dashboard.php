<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="text-muted small mb-1">Ukupno Narudžbi</div>
            <h3><?= $stats['total_orders'] ?></h3>
            <div class="small text-muted">🛒 All time</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card accent">
            <div class="text-muted small mb-1">Ukupni Prihod</div>
            <h3><?= number_format($stats['total_revenue'], 0) ?> KM</h3>
            <div class="small text-muted">💰 Potvrđene narudžbe</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left-color:#ffc107">
            <div class="text-muted small mb-1">Na Čekanju</div>
            <h3 style="color:#ffc107"><?= $stats['pending'] ?></h3>
            <div class="small text-muted">⏳ Narudžbi</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="text-muted small mb-1">Korisnici / Proizvodi</div>
            <h3><?= $stats['total_users'] ?> / <?= $stats['total_products'] ?></h3>
            <div class="small text-muted">👥 Registrovanih</div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-3 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">🕐 Nedavne Narudžbe</h5>
        <a href="/admin/orders" class="btn btn-sm btn-outline-primary">Sve narudžbe</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#ID</th><th>Kupac</th><th>Iznos</th><th>Status</th><th>Datum</th><th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats['recent_orders'] as $o): ?>
                <tr>
                    <td><strong>#<?= $o['id'] ?></strong></td>
                    <td><?= htmlspecialchars($o['user_name'] ?? $o['guest_email'] ?? 'Gost') ?></td>
                    <td class="fw-semibold" style="color:var(--primary)"><?= number_format($o['total'], 2) ?> KM</td>
                    <td><span class="status-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                    <td class="text-muted small"><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
                    <td><a href="/admin/orders/<?= $o['id'] ?>" class="btn btn-xs btn-outline-secondary btn-sm">Detalji</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3 mt-2">
    <div class="col-md-4">
        <a href="/admin/products/new" class="d-block bg-white rounded-3 p-4 text-center text-decoration-none border hover-shadow" style="border-color:var(--border)!important">
            <div style="font-size:2rem">➕</div>
            <div class="fw-semibold mt-2">Dodaj Proizvod</div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/admin/orders" class="d-block bg-white rounded-3 p-4 text-center text-decoration-none border" style="border-color:var(--border)!important">
            <div style="font-size:2rem">📦</div>
            <div class="fw-semibold mt-2">Upravljaj Narudžbama</div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/" class="d-block bg-white rounded-3 p-4 text-center text-decoration-none border" style="border-color:var(--border)!important">
            <div style="font-size:2rem">🌐</div>
            <div class="fw-semibold mt-2">Pregledaj Sajt</div>
        </a>
    </div>
</div>
