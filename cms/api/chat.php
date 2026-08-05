<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/store.php';
require_once __DIR__ . '/../inc/settings.php';

function respond(bool $ok, string $reply = '', string $error = ''): void {
    echo json_encode(['success' => $ok, 'reply' => $reply, 'error' => $error], JSON_UNESCAPED_UNICODE);
    exit;
}

$s = get_settings();

if (empty($s['chat_widget_enabled'])) {
    respond(false, '', 'disabled');
}

$provider = $s['chat_widget_provider'] ?? 'openai';
$apiKey = $provider === 'grok' ? ($s['grok_key'] ?? '') : ($s['openai_key'] ?? '');

if ($apiKey === '') {
    respond(false, '', 'no_key');
}

// --- Rate limiting: max 15 messages / 10 minutes per IP ---
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$attempts = read_json('chat_attempts.json', []);
$now = time();
foreach ($attempts as $k => $v) { if ($now - $v['first'] > 600) unset($attempts[$k]); }
$entry = $attempts[$ip] ?? ['count' => 0, 'first' => $now];
if ($now - $entry['first'] > 600) $entry = ['count' => 0, 'first' => $now];
if ($entry['count'] >= 15) {
    respond(false, '', 'rate_limited');
}
$entry['count']++;
$attempts[$ip] = $entry;
write_json('chat_attempts.json', $attempts);

$message = trim((string)($_POST['message'] ?? ''));
if ($message === '' || mb_strlen($message) > 800) {
    respond(false, '', 'invalid_message');
}

$historyRaw = $_POST['history'] ?? '[]';
$history = json_decode((string)$historyRaw, true);
if (!is_array($history)) $history = [];
$history = array_slice($history, -8);

$cleanHistory = [];
foreach ($history as $h) {
    if (!is_array($h)) continue;
    $role = ($h['role'] ?? '') === 'assistant' ? 'assistant' : 'user';
    $content = mb_substr(trim((string)($h['content'] ?? '')), 0, 800);
    if ($content === '') continue;
    $cleanHistory[] = ['role' => $role, 'content' => $content];
}

$fmt = fn($v) => rtrim(rtrim(number_format((float)$v, 2, '.', ''), '0'), '.');

$priceLines = [];
$priceLabels = ['B' => 'B', 'A' => 'A/A1/A2', 'C' => 'C', 'CCE' => 'C+CE', 'CCED' => 'C+CE+D', 'D' => 'D', 'CE' => 'CE', 'BE' => 'BE', 'LOST' => 'licencijos grąžinimas / restoration'];
foreach ($priceLabels as $key => $label) {
    if (isset($s['price_full'][$key])) {
        $priceLines[] = "{$label}: {$fmt($s['price_full'][$key])} EUR";
    }
}

$systemPrompt = "You are the official virtual assistant embedded on the website of \"" . SITE_NAME . "\", a driving school (vairavimo mokykla) in Vilnius, Lithuania — website autoteises.lt.\n\n"
    . "ALWAYS reply in the same language the visitor's latest message is written in (Lithuanian, Ukrainian, English, or Russian) — detect it automatically. Keep answers short and friendly: 2-4 sentences, no long essays.\n\n"
    . "VERIFIED FACTS about the school (never contradict these, never invent other facts, prices or legal requirements):\n"
    . "- Full course prices by category: " . implode('; ', $priceLines) . ".\n"
    . "- Price is the same whether the student takes practice only or theory plus practice together.\n"
    . "- Address: {$s['address']}, Vilnius.\n"
    . "- Phone: {$s['phone1']} / {$s['phone2']}.\n"
    . "- Email: {$s['email']}.\n"
    . "- Working hours (LT): {$s['hours_lt']}.\n\n"
    . "BEHAVIOUR RULES:\n"
    . "1. Only discuss topics related to this driving school: categories, prices, requirements, enrollment process, contact details, working hours.\n"
    . "2. If asked something unrelated to driving lessons/this school, politely say you can only help with driving-school questions and suggest contacting the school directly.\n"
    . "3. To actually sign up, always direct the visitor to fill in the contact form on the website or call/email the school — you cannot register them yourself.\n"
    . "4. If unsure about a specific legal detail (exact age exceptions, medical certificate rules, etc.), say the exact conditions should be confirmed with the school directly instead of guessing.\n"
    . "5. Never claim to be a human; if asked, say you are the site's AI assistant.\n"
    . "6. Do not output markdown tables or code blocks; plain conversational text only.";

$messages = array_merge(
    [['role' => 'system', 'content' => $systemPrompt]],
    $cleanHistory,
    [['role' => 'user', 'content' => $message]]
);

if ($provider === 'grok') {
    $url = 'https://api.x.ai/v1/chat/completions';
    $model = 'grok-2-latest';
} else {
    $url = 'https://api.openai.com/v1/chat/completions';
    $model = 'gpt-4o-mini';
}

$payload = json_encode([
    'model' => $model,
    'messages' => $messages,
    'max_tokens' => 350,
    'temperature' => 0.4,
], JSON_UNESCAPED_UNICODE);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_CONNECTTIMEOUT => 6,
]);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr = curl_error($ch);
curl_close($ch);

if ($result === false || $httpCode >= 400) {
    error_log('chat.php API error: ' . $curlErr . ' | HTTP ' . $httpCode . ' | ' . substr((string)$result, 0, 500));
    respond(false, '', 'api_error');
}

$data = json_decode((string)$result, true);
$reply = $data['choices'][0]['message']['content'] ?? '';

if ($reply === '') {
    respond(false, '', 'empty_reply');
}

respond(true, trim($reply));
