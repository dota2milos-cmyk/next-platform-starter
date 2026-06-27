<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <title><?= htmlspecialchars($pageTitle ?? 'POD Platforma') ?> | PrintCraft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="/public/css/style.css" rel="stylesheet">
</head>
<body>

<?php if (isset($_SESSION['flash'])): ?>
<div id="flashMessage" data-message="<?= htmlspecialchars($_SESSION['flash']['msg']) ?>" data-type="<?= $_SESSION['flash']['type'] ?>"></div>
<?php unset($_SESSION['flash']); endif; ?>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="/">Print<span>Craft</span></a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/products">Proizvodi</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Kategorije</a>
                    <ul class="dropdown-menu">
                        <?php foreach (Product::categories() as $cat): ?>
                        <li><a class="dropdown-item" href="/products?category=<?= $cat['slug'] ?>"><?= $cat['icon'] ?> <?= htmlspecialchars($cat['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link position-relative" href="/cart">
                        🛒 Košarica
                        <?php $cartCount = Cart::count(); if ($cartCount > 0): ?>
                        <span class="cart-badge cart-count"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if (Auth::user()): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">👤 <?= htmlspecialchars(Auth::user()['name']) ?></a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/orders">Moje Narudžbe</a></li>
                        <?php if (Auth::isAdmin()): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/admin">🛠️ Admin Panel</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/logout">Odjava</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="/login">Prijava</a></li>
                <li class="nav-item"><a class="btn btn-accent btn-sm ms-1" href="/register">Registracija</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main>
<?= $content ?? '' ?>
</main>

<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h6>🎨 PrintCraft POD</h6>
                <p style="font-size:0.9rem">Vaša Print on Demand platforma za sve kategorije proizvoda. Dizajnirajte, naručite, preuzmite.</p>
            </div>
            <div class="col-md-2">
                <h6>Kategorije</h6>
                <a href="/products?category=odjeca">👕 Odjeća</a>
                <a href="/products?category=kucni-dekor">🏠 Kućni Dekor</a>
                <a href="/products?category=knjige-foto">📚 Knjige & Foto</a>
                <a href="/products?category=aksesoari">🎒 Aksesoari</a>
            </div>
            <div class="col-md-2">
                <h6>Info</h6>
                <a href="#">O nama</a>
                <a href="#">Dostava & Povrat</a>
                <a href="#">FAQ</a>
                <a href="#">Kontakt</a>
            </div>
            <div class="col-md-4">
                <h6>📬 Newsletter</h6>
                <form class="d-flex gap-2">
                    <input type="email" class="form-control form-control-sm" placeholder="Vaš email">
                    <button class="btn btn-accent btn-sm">Prijava</button>
                </form>
                <div class="mt-3 d-flex gap-3">
                    <span style="font-size:1.5rem">📘</span>
                    <span style="font-size:1.5rem">📸</span>
                    <span style="font-size:1.5rem">🐦</span>
                </div>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,0.1); margin-top:40px">
        <p class="text-center mb-0" style="font-size:0.85rem">© 2025 PrintCraft POD. Sva prava zadržana. 🇧🇦</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/public/js/app.js"></script>
</body>
</html>
