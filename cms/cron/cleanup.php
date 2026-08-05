<?php
/**
 * Daily cron — prune old login attempts, trim activity log, refresh demo flag.
 * Example crontab (Hostinger):
 *   15 3 * * * /usr/bin/php /home/.../public_html/driving-school/cms/cron/cleanup.php
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli' && empty($_GET['key'])) {
    http_response_code(403);
    exit('CLI or ?key= required');
}

require_once dirname(__DIR__) . '/inc/config.php';
require_once dirname(__DIR__) . '/inc/store.php';

$secret = getenv('DS_CRON_KEY') ?: 'bilohash-ds-demo-cron';
if (PHP_SAPI !== 'cli' && (string) ($_GET['key'] ?? '') !== $secret) {
    http_response_code(403);
    exit('Forbidden');
}

$report = ['ok' => true, 'at' => date('c'), 'actions' => []];

// Login attempts older than 24h
$attempts = read_json('login_attempts.json', []);
if (is_array($attempts)) {
    $kept = [];
    foreach ($attempts as $ip => $row) {
        if (is_array($row) && (time() - (int) ($row['last'] ?? 0)) < 86400) {
            $kept[$ip] = $row;
        }
    }
    write_json('login_attempts.json', $kept);
    $report['actions'][] = 'login_attempts_pruned=' . (count($attempts) - count($kept));
}

// Activity log: keep last 200 entries
$log = read_json('activity_log.json', []);
if (is_array($log) && count($log) > 200) {
    $log = array_slice($log, -200);
    write_json('activity_log.json', array_values($log));
    $report['actions'][] = 'activity_log_trimmed=200';
}

// Touch demo marker
write_json('cron_last.json', [
    'last_run' => date('Y-m-d H:i:s'),
    'mode' => defined('DS_DEMO_MODE') && DS_DEMO_MODE ? 'demo' : 'live',
    'site' => SITE_URL,
]);
$report['actions'][] = 'cron_last_updated';

$out = json_encode($report, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
if (PHP_SAPI === 'cli') {
    echo $out;
} else {
    header('Content-Type: application/json; charset=UTF-8');
    echo $out;
}
