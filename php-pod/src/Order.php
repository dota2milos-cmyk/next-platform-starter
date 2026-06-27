<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    public static function create(array $data, array $cartItems): int {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO orders (user_id, guest_email, total, payment_method, shipping_name, shipping_address, shipping_city, shipping_zip, shipping_country, note) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $data['user_id'] ?? null,
            $data['guest_email'] ?? null,
            $data['total'],
            $data['payment_method'] ?? 'bank_transfer',
            $data['shipping_name'],
            $data['shipping_address'],
            $data['shipping_city'],
            $data['shipping_zip'],
            $data['shipping_country'] ?? 'Bosnia and Herzegovina',
            $data['note'] ?? '',
        ]);
        $orderId = (int)$db->lastInsertId();
        $istmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size, color, custom_design) VALUES (?,?,?,?,?,?,?)");
        foreach ($cartItems as $item) {
            $istmt->execute([
                $orderId,
                $item['product_id'],
                $item['qty'],
                $item['product']['base_price'],
                $item['size'],
                $item['color'],
                $item['custom_design'] ?? '',
            ]);
        }
        return $orderId;
    }

    public static function find(int $id): ?array {
        $db = getDB();
        $stmt = $db->prepare("SELECT o.*, u.name as user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        if (!$order) return null;
        $items = $db->prepare("SELECT oi.*, p.name as product_name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $items->execute([$id]);
        $order['items'] = $items->fetchAll();
        return $order;
    }

    public static function forUser(int $userId): array {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function all(int $limit = 50, int $offset = 0): array {
        $db = getDB();
        return $db->query("SELECT o.*, u.name as user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT $limit OFFSET $offset")->fetchAll();
    }

    public static function updateStatus(int $id, string $status): void {
        $db = getDB()->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $db->execute([$status, $id]);
    }

    public static function stats(): array {
        $db = getDB();
        return [
            'total_orders' => $db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'total_revenue' => $db->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn(),
            'pending' => $db->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn(),
            'total_users' => $db->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn(),
            'total_products' => $db->query("SELECT COUNT(*) FROM products WHERE active = 1")->fetchColumn(),
            'recent_orders' => $db->query("SELECT o.*, u.name as user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5")->fetchAll(),
        ];
    }
}
