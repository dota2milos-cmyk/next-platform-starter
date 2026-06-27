<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/setup.php';
require_once __DIR__ . '/src/Auth.php';
require_once __DIR__ . '/src/Cart.php';
require_once __DIR__ . '/src/Product.php';
require_once __DIR__ . '/src/Order.php';

setupDatabase();

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Simple router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

// Static files
if (preg_match('#^/public/#', $uri)) {
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $types = ['css'=>'text/css','js'=>'application/javascript','png'=>'image/png','jpg'=>'image/jpeg','gif'=>'image/gif','svg'=>'image/svg+xml','woff2'=>'font/woff2'];
        header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
        readfile($file);
        exit;
    }
}

// Uploads
if (preg_match('#^/uploads/#', $uri)) {
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        header('Content-Type: image/' . ($ext === 'jpg' ? 'jpeg' : $ext));
        readfile($file);
        exit;
    }
}

function flash(string $msg, string $type = 'success'): void {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function renderAdmin(string $view, array $data = []): void {
    extract($data);
    ob_start();
    require __DIR__ . '/views/admin/' . $view . '.php';
    $content = ob_get_clean();
    require __DIR__ . '/views/admin/layout.php';
}

function render(string $view, array $data = []): void {
    extract($data);
    ob_start();
    require __DIR__ . '/views/' . $view . '.php';
    $content = ob_get_clean();
    require __DIR__ . '/views/layout.php';
}

// =========================================================
// Routes
// =========================================================

// HOME
if ($uri === '/' && $method === 'GET') {
    render('home', [
        'pageTitle' => 'Početna',
        'categories' => Product::categories(),
        'featured' => Product::all(['limit' => 8]),
    ]);
    exit;
}

// PRODUCTS LIST
if ($uri === '/products' && $method === 'GET') {
    $category = $_GET['category'] ?? '';
    $search = $_GET['search'] ?? '';
    render('products', [
        'pageTitle' => 'Proizvodi',
        'products' => Product::all(['category' => $category, 'search' => $search]),
        'categories' => Product::categories(),
        'activeCategory' => $category,
        'search' => $search,
    ]);
    exit;
}

// PRODUCT DETAIL
if (preg_match('#^/product/(\d+)$#', $uri, $m) && $method === 'GET') {
    $product = Product::find((int)$m[1]);
    if (!$product) { http_response_code(404); echo '404 Not Found'; exit; }
    render('product', [
        'pageTitle' => $product['name'],
        'product' => $product,
        'reviews' => Product::reviews((int)$m[1]),
    ]);
    exit;
}

// PRODUCT REVIEW
if (preg_match('#^/product/(\d+)/review$#', $uri, $m) && $method === 'POST') {
    Auth::requireLogin();
    $rating = max(1, min(5, (int)($_POST['rating'] ?? 5)));
    $comment = trim($_POST['comment'] ?? '');
    Product::addReview((int)$m[1], (int)Auth::user()['id'], $rating, $comment);
    flash('Recenzija dodana! Hvala.');
    redirect('/product/' . $m[1]);
}

// CART
if ($uri === '/cart' && $method === 'GET') {
    $cartItems = Cart::getWithProducts();
    $total = array_sum(array_column($cartItems, 'subtotal'));
    render('cart', ['pageTitle' => 'Košarica', 'cartItems' => $cartItems, 'total' => $total]);
    exit;
}

if ($uri === '/cart/add' && $method === 'POST') {
    $productId = (int)($_POST['product_id'] ?? 0);
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    $size = trim($_POST['size'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $design = '';

    if (!empty($_FILES['custom_design']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['custom_design']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','svg','webp'])) {
            $uploadDir = __DIR__ . '/uploads/designs/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $fname = uniqid('design_') . '.' . $ext;
            move_uploaded_file($_FILES['custom_design']['tmp_name'], $uploadDir . $fname);
            $design = $fname;
        }
    }

    Cart::add($productId, $qty, $size, $color, $design);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'count' => Cart::count()]);
    exit;
}

if ($uri === '/cart/update' && $method === 'POST') {
    Cart::update($_POST['key'] ?? '', (int)($_POST['qty'] ?? 0));
    redirect('/cart');
}

if ($uri === '/cart/remove' && $method === 'POST') {
    Cart::remove($_POST['key'] ?? '');
    redirect('/cart');
}

if ($uri === '/cart/clear' && $method === 'POST') {
    Cart::clear();
    redirect('/cart');
}

// CHECKOUT
if ($uri === '/checkout' && $method === 'GET') {
    $cartItems = Cart::getWithProducts();
    if (empty($cartItems)) redirect('/cart');
    $total = array_sum(array_column($cartItems, 'subtotal'));
    render('checkout', ['pageTitle' => 'Plaćanje', 'cartItems' => $cartItems, 'total' => $total]);
    exit;
}

if ($uri === '/checkout/place' && $method === 'POST') {
    $cartItems = Cart::getWithProducts();
    if (empty($cartItems)) redirect('/cart');
    $total = array_sum(array_column($cartItems, 'subtotal'));

    $user = Auth::user();
    $orderId = Order::create([
        'user_id' => $user['id'] ?? null,
        'guest_email' => $_POST['guest_email'] ?? $user['email'] ?? '',
        'total' => $total,
        'payment_method' => $_POST['payment_method'] ?? 'bank_transfer',
        'shipping_name' => trim($_POST['shipping_name'] ?? ''),
        'shipping_address' => trim($_POST['shipping_address'] ?? ''),
        'shipping_city' => trim($_POST['shipping_city'] ?? ''),
        'shipping_zip' => trim($_POST['shipping_zip'] ?? ''),
        'shipping_country' => trim($_POST['shipping_country'] ?? 'Bosnia and Herzegovina'),
        'note' => trim($_POST['note'] ?? ''),
    ], $cartItems);

    Cart::clear();
    $order = Order::find($orderId);
    render('order-success', ['pageTitle' => 'Narudžba Potvrđena', 'order' => $order]);
    exit;
}

// ORDERS (user)
if ($uri === '/orders' && $method === 'GET') {
    Auth::requireLogin();
    render('orders', [
        'pageTitle' => 'Moje Narudžbe',
        'orders' => Order::forUser((int)Auth::user()['id']),
    ]);
    exit;
}

if (preg_match('#^/order/(\d+)$#', $uri, $m) && $method === 'GET') {
    Auth::requireLogin();
    $order = Order::find((int)$m[1]);
    render('order-success', ['pageTitle' => 'Narudžba #' . $m[1], 'order' => $order]);
    exit;
}

// AUTH
if ($uri === '/login' && $method === 'GET') {
    if (Auth::user()) redirect('/');
    render('login', ['pageTitle' => 'Prijava']);
    exit;
}

if ($uri === '/login' && $method === 'POST') {
    if (Auth::login($_POST['email'] ?? '', $_POST['password'] ?? '')) {
        $redirect = $_GET['redirect'] ?? '/';
        redirect($redirect);
    }
    render('login', ['pageTitle' => 'Prijava', 'error' => 'Pogrešan email ili lozinka.']);
    exit;
}

if ($uri === '/register' && $method === 'GET') {
    if (Auth::user()) redirect('/');
    render('register', ['pageTitle' => 'Registracija']);
    exit;
}

if ($uri === '/register' && $method === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';

    if ($pass !== $confirm) {
        render('register', ['pageTitle' => 'Registracija', 'error' => 'Lozinke se ne podudaraju.']);
        exit;
    }
    $result = Auth::register($name, $email, $pass);
    if ($result === true) {
        Auth::login($email, $pass);
        flash('Dobrodošli, ' . $name . '! 🎉');
        redirect('/');
    }
    render('register', ['pageTitle' => 'Registracija', 'error' => $result]);
    exit;
}

if ($uri === '/logout') {
    Auth::logout();
}

// =========================================================
// ADMIN ROUTES
// =========================================================

if ($uri === '/admin' && $method === 'GET') {
    Auth::requireAdmin();
    renderAdmin('dashboard', ['pageTitle' => 'Dashboard', 'activePage' => 'dashboard', 'stats' => Order::stats()]);
    exit;
}

if ($uri === '/admin/products' && $method === 'GET') {
    Auth::requireAdmin();
    renderAdmin('products', [
        'pageTitle' => 'Proizvodi',
        'activePage' => 'products',
        'products' => Product::all(),
        'categories' => Product::categories(),
        'form' => false,
    ]);
    exit;
}

if ($uri === '/admin/products/new' && $method === 'GET') {
    Auth::requireAdmin();
    renderAdmin('products', [
        'pageTitle' => 'Novi Proizvod',
        'activePage' => 'products',
        'products' => Product::all(),
        'categories' => Product::categories(),
        'form' => true,
        'editProduct' => null,
    ]);
    exit;
}

if (preg_match('#^/admin/products/(\d+)/edit$#', $uri, $m) && $method === 'GET') {
    Auth::requireAdmin();
    $ep = Product::find((int)$m[1]);
    renderAdmin('products', [
        'pageTitle' => 'Uredi Proizvod',
        'activePage' => 'products',
        'products' => Product::all(),
        'categories' => Product::categories(),
        'form' => true,
        'editProduct' => $ep ? array_merge($ep, ['sizes' => json_encode($ep['sizes']), 'colors' => json_encode($ep['colors'])]) : null,
    ]);
    exit;
}

if ($uri === '/admin/products/save' && $method === 'POST') {
    Auth::requireAdmin();
    $data = [
        'id' => !empty($_POST['id']) ? (int)$_POST['id'] : null,
        'category_id' => (int)($_POST['category_id'] ?? 1),
        'name' => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'base_price' => (float)($_POST['base_price'] ?? 0),
        'sizes' => $_POST['sizes'] ?? '[]',
        'colors' => $_POST['colors'] ?? '[]',
        'allow_custom_design' => isset($_POST['allow_custom_design']) ? 1 : 0,
        'stock' => (int)($_POST['stock'] ?? 100),
        'active' => isset($_POST['active']) ? 1 : 0,
    ];

    if (!empty($_FILES['image']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
            $uploadDir = __DIR__ . '/uploads/products/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $fname = uniqid('prod_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fname);
            $data['image'] = $fname;
        }
    }

    Product::save($data);
    flash('Proizvod sačuvan!');
    redirect('/admin/products');
}

if (preg_match('#^/admin/products/(\d+)/delete$#', $uri, $m) && $method === 'POST') {
    Auth::requireAdmin();
    Product::delete((int)$m[1]);
    flash('Proizvod obrisan.');
    redirect('/admin/products');
}

if ($uri === '/admin/orders' && $method === 'GET') {
    Auth::requireAdmin();
    renderAdmin('orders', [
        'pageTitle' => 'Narudžbe',
        'activePage' => 'orders',
        'orders' => Order::all(),
        'orderDetail' => null,
    ]);
    exit;
}

if (preg_match('#^/admin/orders/(\d+)$#', $uri, $m) && $method === 'GET') {
    Auth::requireAdmin();
    renderAdmin('orders', [
        'pageTitle' => 'Narudžba #' . $m[1],
        'activePage' => 'orders',
        'orders' => Order::all(),
        'orderDetail' => Order::find((int)$m[1]),
    ]);
    exit;
}

if (preg_match('#^/admin/orders/(\d+)/status$#', $uri, $m) && $method === 'POST') {
    Auth::requireAdmin();
    $validStatuses = ['pending','processing','shipped','delivered','cancelled'];
    $status = $_POST['status'] ?? 'pending';
    if (in_array($status, $validStatuses)) {
        Order::updateStatus((int)$m[1], $status);
        flash('Status narudžbe ažuriran!');
    }
    redirect('/admin/orders/' . $m[1]);
}

if ($uri === '/admin/users' && $method === 'GET') {
    Auth::requireAdmin();
    $users = getDB()->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
    ob_start();
    ?>
    <div class="bg-white rounded-3 p-4">
        <h5 class="fw-bold mb-3">Korisnici (<?= count($users) ?>)</h5>
        <table class="table table-hover">
            <thead class="table-light"><tr><th>#</th><th>Ime</th><th>Email</th><th>Uloga</th><th>Registrovan</th></tr></thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><span class="badge <?= $u['role'] === 'admin' ? 'bg-danger' : 'bg-primary' ?>"><?= $u['role'] ?></span></td>
                    <td class="text-muted small"><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    $content = ob_get_clean();
    $pageTitle = 'Korisnici'; $activePage = 'users';
    require __DIR__ . '/views/admin/layout.php';
    exit;
}

// 404
http_response_code(404);
ob_start();
?>
<div class="container py-5 text-center">
    <div style="font-size:6rem">🔍</div>
    <h2 class="fw-bold mt-3">Stranica nije pronađena</h2>
    <p class="text-muted">URL: <code><?= htmlspecialchars($uri) ?></code></p>
    <a href="/" class="btn btn-primary mt-2">Idi na Početnu</a>
</div>
<?php
$content = ob_get_clean();
$pageTitle = '404 - Nije Pronađeno';
require __DIR__ . '/views/layout.php';
