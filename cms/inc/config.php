<?php
/**
 * Driving School CMS — BILOHASH demo (Norway).
 * Base path: /driving-school/cms on bilohash.com
 */

// Force UTF-8 everywhere (prevents language-button mojibake)
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}
ini_set('default_charset', 'UTF-8');
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}

// Public URL base (no trailing slash)
define('BASE_PATH', '/driving-school/cms');
define('SITE_URL', 'https://bilohash.com/driving-school/cms');
define('SITE_NAME', 'Nordisk Trafikkskole');

define('CONTACT_EMAIL_TO', 'demo@bilohash.com');
define('CONTACT_EMAIL_FROM', 'noreply@bilohash.com');

// Demo mode — no real Telegram / payment side-effects on bilohash demo
define('DS_DEMO_MODE', true);
define('TELEGRAM_TOKEN', '');
define('YOUR_TELEGRAM_CHAT_ID', 0);

// Fictional demo driving school (Oslo / Norway) — not a real business
define('COMPANY_NAME_LT', 'Nordisk Trafikkskole AS (demo)');
define('COMPANY_NAME_NO', 'Nordisk Trafikkskole AS');
define('COMPANY_CODE', 'DEMO');
define('COMPANY_ADDRESS', 'Karl Johans gate 15, 0154 Oslo');
define('COMPANY_PHONE_1', '+47 21 00 00 00');
define('COMPANY_PHONE_2', '+47 90 00 00 00');
define('COMPANY_MANAGER', 'School director');
define('COMPANY_CITY', 'Oslo');
define('COMPANY_COUNTRY', 'NO');
define('COMPANY_CURRENCY', 'NOK');
define('COMPANY_GEO_LAT', 59.9139);
define('COMPANY_GEO_LNG', 10.7522);

/** Absolute site path: url_path('admin/login.php') => /driving-school/cms/admin/login.php */
function url_path(string $path = ''): string
{
    $base = rtrim(BASE_PATH, '/');
    $path = ltrim(str_replace('\\', '/', $path), '/');
    if ($path === '') {
        return $base === '' ? '/' : $base . '/';
    }
    return ($base === '' ? '' : $base) . '/' . $path;
}

/** Prefix language-relative paths (/en/news.php) with BASE_PATH */
function site_href(string $path): string
{
    if ($path === '' || $path === '/') {
        return url_path('');
    }
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    if (BASE_PATH !== '' && str_starts_with($path, BASE_PATH)) {
        return $path;
    }
    return url_path(ltrim($path, '/'));
}

function asset_url(string $path): string
{
    $path = '/' . ltrim($path, '/');
    $file = dirname(__DIR__) . $path;
    $v = file_exists($file) ? filemtime($file) : time();
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if ($ext === 'css' || $ext === 'js') {
        $base = substr($path, 0, -(strlen($ext) + 1));
        $versioned = $base . '.v' . $v . '.' . $ext;
        return url_path(ltrim($versioned, '/'));
    }
    return url_path(ltrim($path, '/')) . '?v=' . $v;
}
