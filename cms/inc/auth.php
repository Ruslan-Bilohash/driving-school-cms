<?php
require_once __DIR__ . '/store.php';
require_once __DIR__ . '/activity_log.php';

function admin_session_start(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !empty($_SERVER['HTTPS'])]);
        session_start();
    }
}

function default_admin(): array {
    return ['username' => 'admin', 'password_hash' => ''];
}

function get_admin(): array {
    $stored = read_json('admin.json', null);
    if (!is_array($stored) || empty($stored['password_hash'])) {
        return default_admin();
    }
    return $stored;
}

function save_admin(string $username, string $password): bool {
    return write_json('admin.json', [
        'username' => $username,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);
}

function csrf_token(): string {
    admin_session_start();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check(?string $token): bool {
    admin_session_start();
    return !empty($_SESSION['csrf']) && is_string($token) && hash_equals($_SESSION['csrf'], $token);
}

function is_logged_in(): bool {
    admin_session_start();
    return !empty($_SESSION['admin_logged_in']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: ' . BASE_PATH . '/admin/login.php');
        exit;
    }
}

function login_throttle_path(): string {
    return data_path('login_attempts.json');
}

function is_login_throttled(string $ip): bool {
    $attempts = read_json('login_attempts.json', []);
    $entry = $attempts[$ip] ?? null;
    if (!$entry) return false;
    if ($entry['count'] >= 5 && (time() - $entry['last']) < 900) {
        return true;
    }
    return false;
}

function register_login_failure(string $ip): void {
    $attempts = read_json('login_attempts.json', []);
    if (!isset($attempts[$ip]) || (time() - $attempts[$ip]['last']) > 900) {
        $attempts[$ip] = ['count' => 0, 'last' => time()];
    }
    $attempts[$ip]['count']++;
    $attempts[$ip]['last'] = time();
    write_json('login_attempts.json', $attempts);
}

function clear_login_failures(string $ip): void {
    $attempts = read_json('login_attempts.json', []);
    unset($attempts[$ip]);
    write_json('login_attempts.json', $attempts);
}

function attempt_login(string $username, string $password): bool {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    if (is_login_throttled($ip)) {
        return false;
    }
    $admin = get_admin();
    if ($admin['password_hash'] === '') {
        return false;
    }
    if (hash_equals($admin['username'], $username) && password_verify($password, $admin['password_hash'])) {
        clear_login_failures($ip);
        admin_session_start();
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        log_activity('login', 'Ð£ÑÐ¿Ñ–ÑˆÐ½Ð¸Ð¹ Ð²Ñ…Ñ–Ð´');
        return true;
    }
    register_login_failure($ip);
    return false;
}
