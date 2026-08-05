<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/store.php';
require_once __DIR__ . '/../inc/settings.php';
require_once __DIR__ . '/../inc/ai_translate.php';

function fail(string $msg): void {
    echo json_encode(['success' => false, 'message' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    fail('Method not allowed');
}

if (!csrf_check($_POST['csrf'] ?? null)) {
    fail('Ð¡ÐµÑÑ–Ñ Ð·Ð°ÑÑ‚Ð°Ñ€Ñ–Ð»Ð°, Ð¾Ð½Ð¾Ð²Ñ–Ñ‚ÑŒ ÑÑ‚Ð¾Ñ€Ñ–Ð½ÐºÑƒ.');
}

$topic = trim((string)($_POST['topic'] ?? ''));
$lang = (string)($_POST['lang'] ?? 'no');
$langNames = [
    'no' => 'Norwegian',
    'en' => 'English',
    'sv' => 'Swedish',
    'pl' => 'Polish',
    'uk' => 'Ukrainian',
    'ru' => 'Russian',
    'lt' => 'Lithuanian',
];
if (!isset($langNames[$lang])) {
    $lang = 'no';
}

if ($topic === '' || mb_strlen($topic) > 300) {
    fail('ÐžÐ¿Ð¸ÑˆÑ–Ñ‚ÑŒ Ñ‚ÐµÐ¼Ñƒ ÑÑ‚Ð°Ñ‚Ñ‚Ñ– (Ð´Ð¾ 300 ÑÐ¸Ð¼Ð²Ð¾Ð»Ñ–Ð²).');
}

$s = get_settings();
$provider = $s['chat_widget_provider'] ?? 'openai';
$apiKey = $provider === 'grok' ? ($s['grok_key'] ?? '') : ($s['openai_key'] ?? '');

if ($apiKey === '') {
    fail('API-ÐºÐ»ÑŽÑ‡ Ð½Ðµ Ð½Ð°Ð»Ð°ÑˆÑ‚Ð¾Ð²Ð°Ð½Ð¾. Ð”Ð¾Ð´Ð°Ð¹Ñ‚Ðµ Ð¹Ð¾Ð³Ð¾ Ð² ÐÐ°Ð»Ð°ÑˆÑ‚ÑƒÐ²Ð°Ð½Ð½Ñ â†’ API-ÐºÐ»ÑŽÑ‡Ñ–.');
}

$priceLines = [];
$priceLabels = ['B' => 'B', 'A' => 'A/A1/A2', 'C' => 'C', 'CCE' => 'C+CE', 'CCED' => 'C+CE+D', 'D' => 'D', 'CE' => 'CE', 'BE' => 'BE', 'LOST' => 'licence reinstatement'];
foreach ($priceLabels as $key => $label) {
    if (isset($s['price_full'][$key])) {
        $priceLines[] = "{$label}: {$s['price_full'][$key]} EUR";
    }
}

$systemPrompt = "You are a professional content writer for \"" . SITE_NAME . "\", a driving school (vairavimo mokykla) in Vilnius, Lithuania â€” website autoteises.lt.\n\n"
    . "Write a genuinely useful, SEO-friendly blog/news article for the site's news section, in " . $langNames[$lang] . " language, based on the topic the admin gives you.\n\n"
    . "VERIFIED FACTS you may reference (never invent other prices, legal requirements, or facts):\n"
    . "- Course categories and prices: " . implode('; ', $priceLines) . ".\n"
    . "- Address: {$s['address']}, Vilnius. Phone: {$s['phone1']}. Email: {$s['email']}.\n\n"
    . "STYLE RULES:\n"
    . "1. 300-500 words. Practical, friendly, and genuinely helpful tone â€” not generic filler.\n"
    . "2. Structure the content with semantic HTML: a few <h2> (and optional <h3>) subheadings, <p> paragraphs, and <ul><li> lists where useful. No <h1> (the page already has one).\n"
    . "3. Do not invent specific legal age limits, exam rules, or facts beyond common general knowledge and the verified facts above â€” if unsure, keep advice general (e.g. 'confirm exact requirements with the school').\n"
    . "4. You may naturally suggest the reader contact the school or check the site's categories/prices, without inventing URLs.\n"
    . "5. Do not use markdown syntax (no **, no #, no \"* item\" bullets) â€” output real, properly paired HTML tags only. Every <li> must be inside an opening <ul> or <ol> and have its own matching </li>. Never leave a bare \"* text\" line.\n\n"
    . "Respond ONLY with a single valid JSON object (no markdown fences) with exactly these keys:\n"
    . "{\"title\": string, \"excerpt\": string (1-2 sentences, under 160 chars), \"meta_description\": string (under 160 chars), \"content\": string (the HTML body)}";

$messages = [
    ['role' => 'system', 'content' => $systemPrompt],
    ['role' => 'user', 'content' => "Topic: " . $topic],
];

if ($provider === 'grok') {
    $url = 'https://api.x.ai/v1/chat/completions';
    $model = 'grok-2-latest';
    $payload = [
        'model' => $model,
        'messages' => $messages,
        'max_tokens' => 1200,
        'temperature' => 0.6,
    ];
} else {
    $url = 'https://api.openai.com/v1/chat/completions';
    $model = 'gpt-4o-mini';
    $payload = [
        'model' => $model,
        'messages' => $messages,
        'max_tokens' => 1200,
        'temperature' => 0.6,
        'response_format' => ['type' => 'json_object'],
    ];
}

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 45,
    CURLOPT_CONNECTTIMEOUT => 8,
]);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr = curl_error($ch);
curl_close($ch);

if ($result === false || $httpCode >= 400) {
    error_log('ai-generate.php API error: ' . $curlErr . ' | HTTP ' . $httpCode . ' | ' . substr((string)$result, 0, 500));
    fail('ÐÐµ Ð²Ð´Ð°Ð»Ð¾ÑÑ Ð·Ð³ÐµÐ½ÐµÑ€ÑƒÐ²Ð°Ñ‚Ð¸ ÑÑ‚Ð°Ñ‚Ñ‚ÑŽ. Ð¡Ð¿Ñ€Ð¾Ð±ÑƒÐ¹Ñ‚Ðµ Ñ‰Ðµ Ñ€Ð°Ð·.');
}

$data = json_decode((string)$result, true);
$raw = $data['choices'][0]['message']['content'] ?? '';

// Strip markdown code fences if the model added them anyway
$raw = trim($raw);
$raw = preg_replace('/^```(json)?/i', '', $raw);
$raw = preg_replace('/```$/', '', $raw);
$raw = trim($raw);

$article = json_decode($raw, true);

if (!is_array($article) || empty($article['title']) || empty($article['content'])) {
    fail('AI Ð¿Ð¾Ð²ÐµÑ€Ð½ÑƒÐ² Ð½ÐµÐ²Ñ–Ñ€Ð½Ð¸Ð¹ Ñ„Ð¾Ñ€Ð¼Ð°Ñ‚. Ð¡Ð¿Ñ€Ð¾Ð±ÑƒÐ¹Ñ‚Ðµ Ñ‰Ðµ Ñ€Ð°Ð· Ð°Ð±Ð¾ Ð¿ÐµÑ€ÐµÑ„Ñ€Ð°Ð·ÑƒÐ¹Ñ‚Ðµ Ñ‚ÐµÐ¼Ñƒ.');
}

echo json_encode([
    'success' => true,
    'title' => (string)$article['title'],
    'excerpt' => (string)($article['excerpt'] ?? ''),
    'meta_description' => (string)($article['meta_description'] ?? ''),
    'content' => sanitize_ai_html((string)$article['content']),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
