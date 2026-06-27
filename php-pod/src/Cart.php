<?php
class Cart {
    public static function get(): array {
        return $_SESSION['cart'] ?? [];
    }

    public static function add(int $productId, int $qty, string $size = '', string $color = '', string $design = ''): void {
        $key = $productId . '_' . $size . '_' . $color;
        $cart = self::get();
        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'product_id' => $productId,
                'qty' => $qty,
                'size' => $size,
                'color' => $color,
                'custom_design' => $design,
            ];
        }
        $_SESSION['cart'] = $cart;
    }

    public static function update(string $key, int $qty): void {
        if ($qty <= 0) {
            self::remove($key);
            return;
        }
        $_SESSION['cart'][$key]['qty'] = $qty;
    }

    public static function remove(string $key): void {
        unset($_SESSION['cart'][$key]);
    }

    public static function clear(): void {
        $_SESSION['cart'] = [];
    }

    public static function count(): int {
        return array_sum(array_column(self::get(), 'qty'));
    }

    public static function total(array $products): float {
        $total = 0;
        foreach (self::get() as $key => $item) {
            $p = $products[$item['product_id']] ?? null;
            if ($p) $total += $p['base_price'] * $item['qty'];
        }
        return $total;
    }

    public static function getWithProducts(): array {
        $cart = self::get();
        if (empty($cart)) return [];
        $db = getDB();
        $ids = array_unique(array_column($cart, 'product_id'));
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $products = [];
        foreach ($stmt->fetchAll() as $p) $products[$p['id']] = $p;

        $result = [];
        foreach ($cart as $key => $item) {
            $p = $products[$item['product_id']] ?? null;
            if ($p) {
                $result[$key] = array_merge($item, ['product' => $p, 'subtotal' => $p['base_price'] * $item['qty']]);
            }
        }
        return $result;
    }
}
