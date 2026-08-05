<?php
/**
 * Optional: re-seed demo leads if orders.json is empty (safe for portfolio demo).
 *   php cron/seed-demo.php
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

$orders = read_json('orders.json', []);
if (is_array($orders) && count($orders) > 0) {
    echo "orders already present: " . count($orders) . "\n";
    exit(0);
}

$seed = [
    [
        'name' => 'Ole Hansen', 'phone' => '+47 900 11 001', 'email' => 'ole.hansen@example.no',
        'category' => 'B', 'message' => 'Vil starte klasse B i august.', 'lang' => 'no',
        'source_page' => '/', 'status' => 'new', 'email_sent' => false, 'telegram_sent' => false,
        'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')), 'id' => 1,
    ],
    [
        'name' => 'Ingrid Berg', 'phone' => '+47 900 22 002', 'email' => 'ingrid.berg@example.no',
        'category' => 'A', 'message' => 'Motorsykkel A2 kveldskurs.', 'lang' => 'no',
        'source_page' => '/kategorija-a.php', 'status' => 'contacted', 'email_sent' => false, 'telegram_sent' => false,
        'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')), 'id' => 2,
    ],
    [
        'name' => 'Anna Demo', 'phone' => '+47 900 33 003', 'email' => 'anna@example.com',
        'category' => 'B', 'message' => 'English group available?', 'lang' => 'en',
        'source_page' => '/en/', 'status' => 'new', 'email_sent' => false, 'telegram_sent' => false,
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')), 'id' => 3,
    ],
];
write_json('orders.json', $seed);
echo "seeded " . count($seed) . " demo orders\n";
