<?php
declare(strict_types=1);

/**
 * Lead endpoint: contact form + callback popup.
 * Saves the request to orders.json (with marketing attribution), sends email and optional Telegram.
 */

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
require_once __DIR__ . '/../inc/marketing.php';

$settings = get_settings();
$recipientEmail = (string) ($settings['email'] ?: CONTACT_EMAIL_TO);

function respond(bool $ok, string $message): void {
    echo json_encode(['success' => $ok, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

$messages = [
    'no' => [
        'fields' => 'Vennligst fyll ut de obligatoriske feltene.',
        'ok' => 'Takk! Forespørselen din er mottatt — vi kontakter deg snart.',
        'fail' => 'Kunne ikke sende forespørselen. Prøv igjen eller skriv til ' . $recipientEmail,
    ],
    'en' => [
        'fields' => 'Please fill in the required fields.',
        'ok' => 'Thank you! Your request has been received — we will contact you shortly.',
        'fail' => 'Failed to send your request. Please try again or email ' . $recipientEmail,
    ],
    'sv' => [
        'fields' => 'Fyll i de obligatoriska fälten.',
        'ok' => 'Tack! Din förfrågan har tagits emot — vi kontaktar dig snart.',
        'fail' => 'Det gick inte att skicka förfrågan. Försök igen eller mejla ' . $recipientEmail,
    ],
    'pl' => [
        'fields' => 'Prosimy wypełnić wymagane pola.',
        'ok' => 'Dziękujemy! Zgłoszenie zostało przyjęte — wkrótce się skontaktujemy.',
        'fail' => 'Nie udało się wysłać zgłoszenia. Spróbuj ponownie lub napisz na ' . $recipientEmail,
    ],
    'uk' => [
        'fields' => 'Будь ласка, заповніть обов\'язкові поля.',
        'ok' => 'Дякуємо! Вашу заявку отримано — ми зв\'яжемося найближчим часом.',
        'fail' => 'Не вдалося надіслати заявку. Спробуйте ще раз або напишіть на ' . $recipientEmail,
    ],
    'ru' => [
        'fields' => 'Пожалуйста, заполните обязательные поля.',
        'ok' => 'Спасибо! Ваша заявка получена — мы свяжемся с вами в ближайшее время.',
        'fail' => 'Не удалось отправить заявку. Попробуйте ещё раз или напишите на ' . $recipientEmail,
    ],
    'lt' => [
        'fields' => 'Prašome užpildyti privalomus laukus.',
        'ok' => 'Ačiū! Jūsų užklausa gauta — susisieksime artimiausiu metu.',
        'fail' => 'Nepavyko išsiųsti užklausos. Bandykite dar kartą arba parašykite ' . $recipientEmail,
    ],
];

// Contact form posts html_lang ("nb" for Norwegian), callback popup posts site lang code ("no")
$lang = strtolower(trim((string) ($_POST['lang'] ?? 'no')));
$langAliases = ['nb' => 'no', 'nn' => 'no', 'ua' => 'uk'];
$lang = $langAliases[$lang] ?? $lang;
if (!isset($messages[$lang])) {
    $lang = 'en';
}
$m = $messages[$lang];

// Honeypot: bots fill hidden "website" field
if (!empty($_POST['website'])) {
    respond(true, $m['ok']);
}

// Timing trap: form must have been open at least 3 seconds
$ts = (int) ($_POST['ts'] ?? 0);
if ($ts > 0 && (time() - $ts) < 3) {
    respond(true, $m['ok']);
}

$name = trim((string) ($_POST['name'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$category = trim((string) ($_POST['category'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));
$consent = !empty($_POST['consent']);

if ($name === '' || $phone === '' || $category === '' || !$consent) {
    respond(false, $m['fields']);
}

if (!recaptcha_verify((string) ($_POST['recaptcha_token'] ?? ''), (string) ($_SERVER['REMOTE_ADDR'] ?? ''))) {
    respond(false, $m['fail']);
}

$name = mb_substr($name, 0, 120);
$phone = mb_substr(preg_replace('/[^0-9+()\s-]/', '', $phone) ?? '', 0, 40);
$category = mb_substr($category, 0, 40);
$message = mb_substr($message, 0, 1000);

if ($phone === '') {
    respond(false, $m['fields']);
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email = '';
}

$sourcePage = parse_url((string) ($_SERVER['HTTP_REFERER'] ?? ''), PHP_URL_PATH) ?: '';

// Marketing attribution (UTM / referrer / landing page / click ID) from hidden mk_* fields
$attribution = marketing_attribution_from_post($_POST);
$sourceSummary = marketing_source_label(['marketing' => $attribution])
    . (!empty($attribution['utm_campaign']) ? ' · ' . $attribution['utm_campaign'] : '')
    . ' [' . $attribution['channel'] . ']';

$siteName = defined('SITE_NAME') ? SITE_NAME : 'Driving School';
$siteHost = (string) parse_url(SITE_URL, PHP_URL_HOST);

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
$rowsHtml .= email_row('Navn / Name', htmlspecialchars($name));
$rowsHtml .= email_row('Telefon / Phone', '<a href="tel:' . htmlspecialchars($phone) . '" style="color:#2f6fed;text-decoration:none">' . htmlspecialchars($phone) . '</a>');
if ($email !== '') {
    $rowsHtml .= email_row('E-post / Email', '<a href="mailto:' . htmlspecialchars($email) . '" style="color:#2f6fed;text-decoration:none">' . htmlspecialchars($email) . '</a>');
}
$rowsHtml .= email_row('Klasse / Category', htmlspecialchars($category));
if ($message !== '') {
    $rowsHtml .= email_row('Melding / Message', nl2br(htmlspecialchars($message)));
}
$rowsHtml .= email_row('Språk / Language', strtoupper($lang));
if ($sourcePage !== '') {
    $rowsHtml .= email_row('Side / Page', htmlspecialchars($sourcePage));
}
$rowsHtml .= email_row('Kilde / Source', htmlspecialchars($sourceSummary));

$htmlBody = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head>'
    . '<body style="margin:0;padding:24px;background:#f4f6fb;font-family:Arial,Helvetica,sans-serif">'
    . '<div style="max-width:560px;margin:0 auto">'
    . '<div style="background:#1d4fc4;padding:26px 30px;border-radius:14px 14px 0 0">'
    . '<span style="color:#ffffff;font-size:19px;font-weight:800">🚗 ' . htmlspecialchars($siteName) . '</span>'
    . '<div style="color:#dbe6ff;font-size:13px;margin-top:5px">Ny forespørsel fra nettsiden / New website request — ' . htmlspecialchars($siteHost) . '</div>'
    . '</div>'
    . '<div style="background:#ffffff;padding:26px 30px;border-radius:0 0 14px 14px;border:1px solid #e7eaf2;border-top:none">'
    . '<table style="width:100%;border-collapse:collapse">' . $rowsHtml . '</table>'
    . '<div style="margin-top:20px;padding-top:16px;border-top:1px solid #eef1f8;font-size:12px;color:#9aa5bd">Sendt automatisk fra kontaktskjemaet / Sent automatically from the contact form — ' . htmlspecialchars($siteHost) . '</div>'
    . '</div>'
    . '</div>'
    . '</body></html>';

$subject = '=?UTF-8?B?' . base64_encode('Ny forespørsel / New request — ' . $siteName) . '?=';
$fromAddress = CONTACT_EMAIL_FROM;
$fromName = '=?UTF-8?B?' . base64_encode($siteName) . '?=';
$headers = "From: {$fromName} <{$fromAddress}>\r\n";
$headers .= "Reply-To: " . ($email !== '' ? $email : $fromAddress) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$emailSent = @mail($recipientEmail, $subject, $htmlBody, $headers);

// --- Telegram (disabled in demo mode / without token) ---
$demoMode = defined('DS_DEMO_MODE') && DS_DEMO_MODE;
if (!$demoMode && defined('TELEGRAM_TOKEN') && TELEGRAM_TOKEN !== '' && function_exists('curl_init')) {
    $tgText = "🚗 New request — {$siteName}\n\n"
        . "Name: {$name}\n"
        . "Phone: {$phone}\n"
        . ($email !== '' ? "Email: {$email}\n" : '')
        . "Category: {$category}\n"
        . ($message !== '' ? "Message: {$message}\n" : '')
        . "Language: " . strtoupper($lang) . "\n"
        . ($sourcePage !== '' ? "Page: {$sourcePage}\n" : '')
        . "Source: {$sourceSummary}";

    $ch = curl_init('https://api.telegram.org/bot' . TELEGRAM_TOKEN . '/sendMessage');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'chat_id' => YOUR_TELEGRAM_CHAT_ID,
            'text' => $tgText,
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 6,
        CURLOPT_CONNECTTIMEOUT => 4,
    ]);
    $result = curl_exec($ch);
    $telegramSent = ($result !== false);
    curl_close($ch);
}

$orderId = append_order([
    'name' => $name,
    'phone' => $phone,
    'email' => $email,
    'category' => $category,
    'message' => $message,
    'lang' => $lang,
    'source_page' => $sourcePage,
    'marketing' => $attribution,
    'status' => 'new',
    'email_sent' => $emailSent,
    'telegram_sent' => $telegramSent,
    'created_at' => date('Y-m-d H:i:s'),
]);

// The request is not lost once it is saved in the admin panel, even if email/Telegram failed
if ($orderId > 0 || $emailSent || $telegramSent) {
    respond(true, $m['ok']);
}

respond(false, $m['fail']);
