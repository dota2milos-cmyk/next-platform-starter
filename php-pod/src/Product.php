<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    public static function all(array $filters = []): array {
        $db = getDB();
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                    COALESCE(AVG(r.rating), 0) as avg_rating,
                    COUNT(r.id) as review_count
                FROM products p
                JOIN categories c ON p.category_id = c.id
                LEFT JOIN reviews r ON r.product_id = p.id
                WHERE p.active = 1";
        $params = [];
        if (!empty($filters['category'])) {
            $sql .= " AND c.slug = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }
        $sql .= " GROUP BY p.id ORDER BY p.created_at DESC";
        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array {
        $db = getDB();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug,
            COALESCE(AVG(r.rating), 0) as avg_rating, COUNT(r.id) as review_count
            FROM products p JOIN categories c ON p.category_id = c.id
            LEFT JOIN reviews r ON r.product_id = p.id
            WHERE p.id = ? GROUP BY p.id");
        $stmt->execute([$id]);
        $p = $stmt->fetch();
        if (!$p) return null;
        $p['sizes'] = json_decode($p['sizes'], true) ?? [];
        $p['colors'] = json_decode($p['colors'], true) ?? [];
        return $p;
    }

    public static function reviews(int $productId): array {
        $db = getDB();
        $stmt = $db->prepare("SELECT r.*, u.name as user_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public static function addReview(int $productId, int $userId, int $rating, string $comment): void {
        $db = getDB();
        $stmt = $db->prepare("INSERT OR REPLACE INTO reviews (product_id, user_id, rating, comment) VALUES (?,?,?,?)");
        $stmt->execute([$productId, $userId, $rating, $comment]);
    }

    public static function categories(): array {
        $db = getDB();
        return $db->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON p.category_id = c.id AND p.active = 1 GROUP BY c.id")->fetchAll();
    }

    public static function save(array $data): int {
        $db = getDB();
        if (!empty($data['id'])) {
            $stmt = $db->prepare("UPDATE products SET category_id=?, name=?, description=?, base_price=?, sizes=?, colors=?, allow_custom_design=?, stock=?, active=? WHERE id=?");
            $stmt->execute([$data['category_id'], $data['name'], $data['description'], $data['base_price'], $data['sizes'], $data['colors'], $data['allow_custom_design'], $data['stock'], $data['active'], $data['id']]);
            return $data['id'];
        }
        $stmt = $db->prepare("INSERT INTO products (category_id, name, description, base_price, image, sizes, colors, allow_custom_design, stock) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$data['category_id'], $data['name'], $data['description'], $data['base_price'], $data['image'] ?? 'default.jpg', $data['sizes'], $data['colors'], $data['allow_custom_design'], $data['stock'] ?? 100]);
        return (int)$db->lastInsertId();
    }

    public static function delete(int $id): void {
        $db = getDB()->prepare("UPDATE products SET active = 0 WHERE id = ?");
        $db->execute([$id]);
    }
}
