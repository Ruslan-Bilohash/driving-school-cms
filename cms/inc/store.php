<?php
define('DATA_DIR', __DIR__ . '/data');

function data_path(string $name): string {
    return DATA_DIR . '/' . $name;
}

function read_json(string $name, $default) {
    $path = data_path($name);
    if (!file_exists($path)) return $default;
    $fh = fopen($path, 'r');
    if (!$fh) return $default;
    flock($fh, LOCK_SH);
    $content = stream_get_contents($fh);
    flock($fh, LOCK_UN);
    fclose($fh);
    $data = json_decode($content, true);
    return $data === null ? $default : $data;
}

function write_json(string $name, $data): bool {
    if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0755, true);
    $path = data_path($name);
    $fh = fopen($path, 'c+');
    if (!$fh) return false;
    flock($fh, LOCK_EX);
    ftruncate($fh, 0);
    rewind($fh);
    fwrite($fh, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    fflush($fh);
    flock($fh, LOCK_UN);
    fclose($fh);
    return true;
}

function append_order(array $order): int {
    if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0755, true);
    $path = data_path('orders.json');
    $fh = fopen($path, 'c+');
    if (!$fh) return 0;
    flock($fh, LOCK_EX);
    $content = stream_get_contents($fh);
    $orders = json_decode((string)$content, true);
    if (!is_array($orders)) $orders = [];
    $nextId = 1;
    foreach ($orders as $o) { if (($o['id'] ?? 0) >= $nextId) $nextId = $o['id'] + 1; }
    $order['id'] = $nextId;
    $orders[] = $order;
    ftruncate($fh, 0);
    rewind($fh);
    fwrite($fh, json_encode($orders, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    fflush($fh);
    flock($fh, LOCK_UN);
    fclose($fh);
    return $order['id'];
}
