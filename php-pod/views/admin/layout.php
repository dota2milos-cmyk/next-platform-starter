<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> | PrintCraft Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="/public/css/style.css" rel="stylesheet">
</head>
<body>
<div class="admin-sidebar">
    <a href="/admin" class="brand">🎨 PrintCraft<br><small style="font-size:0.7rem;opacity:0.6">Admin Panel</small></a>
    <nav class="mt-3">
        <a href="/admin" class="admin-nav-link <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="/admin/products" class="admin-nav-link <?= ($activePage ?? '') === 'products' ? 'active' : '' ?>">📦 Proizvodi</a>
        <a href="/admin/orders" class="admin-nav-link <?= ($activePage ?? '') === 'orders' ? 'active' : '' ?>">🛒 Narudžbe</a>
        <a href="/admin/users" class="admin-nav-link <?= ($activePage ?? '') === 'users' ? 'active' : '' ?>">👥 Korisnici</a>
        <div style="border-top:1px solid rgba(255,255,255,0.1); margin-top:auto; padding-top:20px; margin-top:20px">
            <a href="/" class="admin-nav-link">🌐 Na Sajt</a>
            <a href="/logout" class="admin-nav-link">🚪 Odjava</a>
        </div>
    </nav>
</div>
<div class="admin-content">
    <?php if (isset($_SESSION['flash'])): ?>
    <div id="flashMessage" data-message="<?= htmlspecialchars($_SESSION['flash']['msg']) ?>" data-type="<?= $_SESSION['flash']['type'] ?>"></div>
    <?php unset($_SESSION['flash']); endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h4>
        <div class="text-muted small">👤 <?= htmlspecialchars(Auth::user()['name']) ?> &nbsp; <?= date('d.m.Y H:i') ?></div>
    </div>

    <?= $content ?? '' ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/public/js/app.js"></script>
</body>
</html>
