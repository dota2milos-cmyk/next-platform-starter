<?php
require_once __DIR__ . '/database.php';

function setupDatabase(): void {
    $db = getDB();

    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        role TEXT DEFAULT 'customer',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT UNIQUE NOT NULL,
        icon TEXT DEFAULT '🎨',
        description TEXT
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category_id INTEGER NOT NULL,
        name TEXT NOT NULL,
        description TEXT,
        base_price REAL NOT NULL,
        image TEXT DEFAULT 'default.png',
        sizes TEXT DEFAULT '[]',
        colors TEXT DEFAULT '[]',
        allow_custom_design INTEGER DEFAULT 1,
        stock INTEGER DEFAULT 100,
        active INTEGER DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id)
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        guest_email TEXT,
        total REAL NOT NULL,
        status TEXT DEFAULT 'pending',
        payment_method TEXT DEFAULT 'bank_transfer',
        shipping_name TEXT,
        shipping_address TEXT,
        shipping_city TEXT,
        shipping_zip TEXT,
        shipping_country TEXT DEFAULT 'Bosnia and Herzegovina',
        note TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS order_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL,
        price REAL NOT NULL,
        size TEXT,
        color TEXT,
        custom_design TEXT,
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS reviews (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        rating INTEGER NOT NULL CHECK(rating BETWEEN 1 AND 5),
        comment TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    // Seed categories
    $cats = [
        ['Odjeća', 'odjeca', '👕', 'Majice, hoodies, čarape, kape i još mnogo toga'],
        ['Kućni Dekor', 'kucni-dekor', '🏠', 'Jastuci, posteri, šalice, slike za dom'],
        ['Knjige & Foto', 'knjige-foto', '📚', 'Foto albumi, planeri, self-publishing knjige'],
        ['Aksesoari', 'aksesoari', '🎒', 'Torbe, maske za telefon, privjesci'],
    ];
    $stmt = $db->prepare("INSERT OR IGNORE INTO categories (name, slug, icon, description) VALUES (?, ?, ?, ?)");
    foreach ($cats as $c) $stmt->execute($c);

    // Seed admin user
    $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
    $db->exec("INSERT OR IGNORE INTO users (name, email, password, role) VALUES ('Admin', 'admin@pod.ba', '$adminPass', 'admin')");

    // Seed products
    $products = [
        [1, 'Unisex Premium Majica', 'Visokokvalitetna pamučna majica (180g/m²) sa tvojim dizajnom. Dostupna u svim veličinama.', 29.99, 'tshirt.jpg', '["XS","S","M","L","XL","XXL"]', '["Bijela","Crna","Siva","Navy"]', 1],
        [1, 'Hoodie s Kapuljačom', 'Ugodni hoodie od 300g pamuka. Idealan za print on demand dizajne.', 49.99, 'hoodie.jpg', '["S","M","L","XL","XXL"]', '["Crna","Siva","Bijela","Bordo"]', 1],
        [1, 'Organska Majica', 'Eco-friendly organski pamuk. Certifikovana GOTS majica.', 34.99, 'organic.jpg', '["XS","S","M","L","XL"]', '["Bijela","Zelena","Plava"]', 1],
        [1, 'Baseball Kapa', 'Strukturirana kapa s ravnim obodom. Print ili vez.', 19.99, 'cap.jpg', '["Jedna Veličina"]', '["Crna","Bijela","Navy","Crvena"]', 1],
        [2, 'Jastučnica 45x45cm', 'Kvalitetna jastučnica sa zipom. Full color print.', 24.99, 'pillow.jpg', '["45x45"]', '["Bijela","Siva"]', 1],
        [2, 'Poster A3 / A2 / A1', 'Mat papir 200g. Visoka rezolucija tiska.', 14.99, 'poster.jpg', '["A3","A2","A1"]', '["Mat","Sjajni"]', 1],
        [2, 'Keramička Šalica 330ml', 'Dishwasher safe print. Keramička šalica sa ručkom.', 17.99, 'mug.jpg', '["330ml","450ml"]', '["Bijela","Crna","Crvena"]', 1],
        [2, 'Canvas Slika', 'Galerijski canvas print. Dubina okvira 3cm.', 39.99, 'canvas.jpg', '["20x30","30x40","40x60","50x70"]', '[]', 0],
        [3, 'Foto Album Softcover', 'Personalizirani foto album A4 format, 20-100 stranica.', 22.99, 'photobook.jpg', '["20str","40str","60str","80str","100str"]', '["Softcover","Hardcover"]', 0],
        [3, 'Planer A5 Godišnji', 'Datumski planer sa tvojim dizajnom na koricama.', 18.99, 'planner.jpg', '["A5","A4"]', '["Spirala","Tvrde Korice"]', 0],
        [3, 'Self-Publishing Knjiga', 'Isprintaj svoju knjigu. Offset ili digitalni tisak.', 8.99, 'book.jpg', '["A5","A4","B5"]', '["Softcover","Hardcover"]', 0],
        [4, 'Torba Tote Canvas', 'Čvrsta canvas torba 38x42cm sa ušivenim ručkama.', 19.99, 'tote.jpg', '["Jedna Veličina"]', '["Prirodna","Crna","Navy"]', 1],
    ];
    $pstmt = $db->prepare("INSERT OR IGNORE INTO products (category_id, name, description, base_price, image, sizes, colors, allow_custom_design) VALUES (?,?,?,?,?,?,?,?)");
    foreach ($products as $p) $pstmt->execute($p);
}
