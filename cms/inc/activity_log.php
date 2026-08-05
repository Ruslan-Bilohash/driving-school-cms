<?php
require_once __DIR__ . '/store.php';

function log_activity(string $action, string $details = ''): void {
    admin_session_start();
    $entry = [
        'time' => date('Y-m-d H:i:s'),
        'user' => $_SESSION['admin_username'] ?? 'unknown',
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        'action' => $action,
        'details' => mb_substr($details, 0, 200),
    ];

    $path = data_path('activity_log.json');
    $fh = fopen($path, 'c+');
    if (!$fh) return;
    flock($fh, LOCK_EX);
    $content = stream_get_contents($fh);
    $log = json_decode((string)$content, true);
    if (!is_array($log)) $log = [];
    $log[] = $entry;
    if (count($log) > 500) {
        $log = array_slice($log, -500);
    }
    ftruncate($fh, 0);
    rewind($fh);
    fwrite($fh, json_encode($log, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    fflush($fh);
    flock($fh, LOCK_UN);
    fclose($fh);
}

function get_activity_log(): array {
    $log = read_json('activity_log.json', []);
    if (!is_array($log)) return [];
    return array_reverse($log);
}
