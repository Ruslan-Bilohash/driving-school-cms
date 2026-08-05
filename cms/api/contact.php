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
require_once __DIR__ . '/../inc/recaptcha.php';

$settings = get_settings();
$recipientEmail = $settings['email'] ?: CONTACT_EMAIL_TO;

function respond(bool $ok, string $message): void {
    echo json_encode(['success' => $ok, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

$messages = [
    'lt' => [
        'fields' => 'PraÅ¡ome uÅ¾pildyti privalomus laukus.',
        'ok' => 'AÄiÅ«! JÅ«sÅ³ uÅ¾klausa gauta â€” susisieksime artimiausiu metu.',
        'fail' => 'Nepavyko iÅ¡siÅ³sti uÅ¾klausos. Bandykite dar kartÄ… arba paraÅ¡ykite support@autoteises.lt',
    ],
    'uk' => [
        'fields' => 'Ð‘ÑƒÐ´ÑŒ Ð»Ð°ÑÐºÐ°, Ð·Ð°Ð¿Ð¾Ð²Ð½Ñ–Ñ‚ÑŒ Ð¾Ð±Ð¾Ð²\'ÑÐ·ÐºÐ¾Ð²Ñ– Ð¿Ð¾Ð»Ñ.',
        'ok' => 'Ð”ÑÐºÑƒÑ”Ð¼Ð¾! Ð’Ð°ÑˆÑƒ Ð·Ð°ÑÐ²ÐºÑƒ Ð¾Ñ‚Ñ€Ð¸Ð¼Ð°Ð½Ð¾ â€” Ð¼Ð¸ Ð·Ð²\'ÑÐ¶ÐµÐ¼Ð¾ÑÑ Ð½Ð°Ð¹Ð±Ð»Ð¸Ð¶Ñ‡Ð¸Ð¼ Ñ‡Ð°ÑÐ¾Ð¼.',
        'fail' => 'ÐÐµ Ð²Ð´Ð°Ð»Ð¾ÑÑ Ð½Ð°Ð´Ñ–ÑÐ»Ð°Ñ‚Ð¸ Ð·Ð°ÑÐ²ÐºÑƒ. Ð¡Ð¿Ñ€Ð¾Ð±ÑƒÐ¹Ñ‚Ðµ Ñ‰Ðµ Ñ€Ð°Ð· Ð°Ð±Ð¾ Ð½Ð°Ð¿Ð¸ÑˆÑ–Ñ‚ÑŒ Ð½Ð° support@autoteises.lt',
    ],
    'en' => [
        'fields' => 'Please fill in the required fields.',
        'ok' => 'Thank you! Your request has been received â€” we will contact you shortly.',
        'fail' => 'Failed to send your request. Please try again or email support@autoteises.lt',
    ],
    'ru' => [
        'fields' => 'ÐŸÐ¾Ð¶Ð°Ð»ÑƒÐ¹ÑÑ‚Ð°, Ð·Ð°Ð¿Ð¾Ð»Ð½Ð¸Ñ‚Ðµ Ð¾Ð±ÑÐ·Ð°Ñ‚ÐµÐ»ÑŒÐ½Ñ‹Ðµ Ð¿Ð¾Ð»Ñ.',
        'ok' => 'Ð¡Ð¿Ð°ÑÐ¸Ð±Ð¾! Ð’Ð°ÑˆÐ° Ð·Ð°ÑÐ²ÐºÐ° Ð¿Ð¾Ð»ÑƒÑ‡ÐµÐ½Ð° â€” Ð¼Ñ‹ ÑÐ²ÑÐ¶ÐµÐ¼ÑÑ Ñ Ð²Ð°Ð¼Ð¸ Ð² Ð±Ð»Ð¸Ð¶Ð°Ð¹ÑˆÐµÐµ Ð²Ñ€ÐµÐ¼Ñ.',
        'fail' => 'ÐÐµ ÑƒÐ´Ð°Ð»Ð¾ÑÑŒ Ð¾Ñ‚Ð¿Ñ€Ð°Ð²Ð¸Ñ‚ÑŒ Ð·Ð°ÑÐ²ÐºÑƒ. ÐŸÐ¾Ð¿Ñ€Ð¾Ð±ÑƒÐ¹Ñ‚Ðµ ÐµÑ‰Ñ‘ Ñ€Ð°Ð· Ð¸Ð»Ð¸ Ð½Ð°Ð¿Ð¸ÑˆÐ¸Ñ‚Ðµ Ð½Ð° support@autoteises.lt',
    ],
];

$lang = $_POST['lang'] ?? 'lt';
if (!isset($messages[$lang])) $lang = 'no';
$m = $messages[$lang];

// Honeypot: bots fill hidden "website" field
if (!empty($_POST['website'])) {
    respond(true, $m['ok']);
}

// Timing trap: form must have been open at least 3 seconds
$ts = (int)($_POST['ts'] ?? 0);
if ($ts > 0 && (time() - $ts) < 3) {
    respond(true, $m['ok']);
}

$name = trim((string)($_POST['name'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$category = trim((string)($_POST['category'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));
$consent = !empty($_POST['consent']);

if ($name === '' || $phone === '' || $category === '' || !$consent) {
    respond(false, $m['fields']);
}

if (!recaptcha_verify((string)($_POST['recaptcha_token'] ?? ''), $_SERVER['REMOTE_ADDR'] ?? '')) {
    respond(false, $m['fail']);
}

$name = mb_substr($name, 0, 120);
$phone = preg_replace('/[^0-9+()\s-]/', '', $phone) ?? '';
$category = mb_substr($category, 0, 40);
$message = mb_substr($message, 0, 1000);

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email = '';
}

$sourcePage = parse_url((string)($_SERVER['HTTP_REFERER'] ?? ''), PHP_URL_PATH) ?: '';

$emailSent = false;
$telegramSent = false;

// --- Email (HTML) ---
function email_row(string $label, string $valueHtml): string {
    return '<tr>'
        . '<td style="padding:11px 0;border-bottom:1px solid #eef1f8;color:#5c6577;font-size:13px;width:130px;vertical-align:top">' . htmlspecialchars($label) . '</td>'
        . '<td style="padding:11px 0;border-bottom:1px solid #eef1f8;font-size:14px;font-weight:700;color:#121826;vertical-align:top">' . $valueHtml . '</td>'
        . '</tr>';
}

$rowsHtml = '';
$rowsHtml .= email_row('Vardas', htmlspecialchars($name));
$rowsHtml .= email_row('Telefonas', '<a href="tel:' . htmlspecialchars($phone) . '" style="color:#2f6fed;text-decoration:none">' . htmlspecialchars($phone) . '</a>');
if ($email !== '') {
    $rowsHtml .= email_row('El. paÅ¡tas', '<a href="mailto:' . htmlspecialchars($email) . '" style="color:#2f6fed;text-decoration:none">' . htmlspecialchars($email) . '</a>');
}
$rowsHtml .= email_row('Kategorija', htmlspecialchars($category));
if ($message !== '') {
    $rowsHtml .= email_row('Å½inutÄ—', nl2br(htmlspecialchars($message)));
}
$rowsHtml .= email_row('Kalba', strtoupper($lang));
if ($sourcePage !== '') {
    $rowsHtml .= email_row('Puslapis', htmlspecialchars($sourcePage));
}

$htmlBody = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head>'
    . '<body style="margin:0;padding:24px;background:#f4f6fb;font-family:Arial,Helvetica,sans-serif">'
    . '<div style="max-width:560px;margin:0 auto">'
    . '<div style="background:#1d4fc4;padding:26px 30px;border-radius:14px 14px 0 0">'
    . '<span style="color:#ffffff;font-size:19px;font-weight:800">ðŸš— AutoteisÄ—s</span>'
    . '<div style="color:#dbe6ff;font-size:13px;margin-top:5px">Nauja uÅ¾klausa iÅ¡ svetainÄ—s autoteises.lt</div>'
    . '</div>'
    . '<div style="background:#ffffff;padding:26px 30px;border-radius:0 0 14px 14px;border:1px solid #e7eaf2;border-top:none">'
    . '<table style="width:100%;border-collapse:collapse">' . $rowsHtml . '</table>'
    . '<div style="margin-top:20px;padding-top:16px;border-top:1px solid #eef1f8;font-size:12px;color:#9aa5bd">Å is laiÅ¡kas iÅ¡siÅ³stas automatiÅ¡kai iÅ¡ autoteises.lt kontaktÅ³ formos.</div>'
    . '</div>'
    . '</div>'
    . '</body></html>';

$subject = '=?UTF-8?B?' . base64_encode('Nauja uÅ¾klausa â€” autoteises.lt') . '?=';
$boundaryFrom = CONTACT_EMAIL_FROM;
$headers = "From: AutoteisÄ—s <{$boundaryFrom}>\r\n";
$headers .= "Reply-To: " . ($email !== '' ? $email : $boundaryFrom) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$emailSent = @mail($recipientEmail, $subject, $htmlBody, $headers);

// --- Telegram ---
if (defined('TELEGRAM_TOKEN') && TELEGRAM_TOKEN !== '') {
    $tgText = "ðŸš— *Nauja uÅ¾klausa â€” autoteises.lt*\n\n"
        . "*Vardas:* {$name}\n"
        . "*Tel.:* {$phone}\n"
        . ($email !== '' ? "*El. paÅ¡tas:* {$email}\n" : '')
        . "*Kategorija:* {$category}\n"
        . ($message !== '' ? "*Å½inutÄ—:* {$message}\n" : '')
        . "*Kalba:* " . strtoupper($lang) . "\n"
        . ($sourcePage !== '' ? "*Puslapis:* {$sourcePage}" : '');

    $ch = curl_init("https://api.telegram.org/bot" . TELEGRAM_TOKEN . "/sendMessage");
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'chat_id' => YOUR_TELEGRAM_CHAT_ID,
            'text' => $tgText,
            'parse_mode' => 'Markdown',
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 6,
        CURLOPT_CONNECTTIMEOUT => 4,
    ]);
    $result = curl_exec($ch);
    $telegramSent = ($result !== false);
    curl_close($ch);
}

append_order([
    'name' => $name,
    'phone' => $phone,
    'email' => $email,
    'category' => $category,
    'message' => $message,
    'lang' => $lang,
    'source_page' => $sourcePage,
    'status' => 'new',
    'email_sent' => $emailSent,
    'telegram_sent' => $telegramSent,
    'created_at' => date('Y-m-d H:i:s'),
]);

if ($emailSent || $telegramSent) {
    respond(true, $m['ok']);
}

respond(false, $m['fail']);
