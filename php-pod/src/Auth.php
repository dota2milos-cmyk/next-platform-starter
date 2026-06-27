<?php
require_once __DIR__ . '/../config/database.php';

class Auth {
    public static function login(string $email, string $password): bool {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            return true;
        }
        return false;
    }

    public static function register(string $name, string $email, string $password): bool|string {
        $db = getDB();
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);
            return true;
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'UNIQUE')) return 'Email već postoji.';
            return 'Greška pri registraciji.';
        }
    }

    public static function logout(): void {
        session_destroy();
        header('Location: /');
        exit;
    }

    public static function user(): ?array {
        return isset($_SESSION['user_id']) ? [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role'],
        ] : null;
    }

    public static function isAdmin(): bool {
        return ($_SESSION['user_role'] ?? '') === 'admin';
    }

    public static function requireLogin(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }
    }

    public static function requireAdmin(): void {
        self::requireLogin();
        if (!self::isAdmin()) {
            http_response_code(403);
            die('Pristup zabranjen.');
        }
    }
}
