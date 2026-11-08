<?php
/**
 * Marketing module — Driving School CMS.
 *
 * - Analytics IDs (GA4, Google Tag Manager, Meta Pixel) with GDPR consent gate
 * - Lead attribution (UTM, referrer, landing page, click IDs) stored on every request
 * - Promo banner above the site header (multi-language, schedule, dismissible)
 * - Lead statistics for the admin "Marketing" page + CSV export
 *
 * Storage: cms/inc/data/marketing.json (JSON, no database).
 */
require_once __DIR__ . '/store.php';
require_once __DIR__ . '/languages.php';

const MARKETING_UTM_KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
const MARKETING_BANNER_STYLES = ['primary', 'accent', 'success', 'dark'];
const MARKETING_CHANNELS = ['paid', 'social', 'organic', 'email', 'referral', 'campaign', 'direct', 'unknown'];

/** Public site languages (banner texts are edited per language). */
function marketing_langs(): array {
    return array_keys(site_languages());
}

function default_marketing(): array {
    return [
        'ga4_id' => '',
        'gtm_id' => '',
        'meta_pixel_id' => '',
        'require_consent' => true,
        'track_leads' => true,
        'track_clicks' => true,
        'attribution_days' => 90,
        'banner' => [
            'enabled' => false,
            'style' => 'accent',
            'dismissible' => true,
            'starts_at' => '',
            'ends_at' => '',
            'link' => '#contact',
            'text_no' => 'Høstkampanje: 10 % rabatt på komplett klasse B-kurs ved påmelding denne måneden.',
            'cta_no' => 'Meld deg på',
            'text_en' => 'Autumn offer: 10% off the full category B course when you sign up this month.',
            'cta_en' => 'Sign up',
            'text_sv' => 'Höstkampanj: 10 % rabatt på hela B-kursen vid anmälan denna månad.',
            'cta_sv' => 'Anmäl dig',
            'text_pl' => 'Jesienna promocja: 10% zniżki na pełny kurs kat. B przy zapisie w tym miesiącu.',
            'cta_pl' => 'Zapisz się',
            'text_uk' => 'Осіння акція: −10% на повний курс категорії B при записі цього місяця.',
            'cta_uk' => 'Записатися',
            'text_ru' => 'Осенняя акция: −10% на полный курс категории B при записи в этом месяце.',
            'cta_ru' => 'Записаться',
            'text_lt' => 'Rudens akcija: −10 % visam B kategorijos kursui užsiregistravus šį mėnesį.',
            'cta_lt' => 'Registruotis',
        ],
    ];
}

function get_marketing(): array {
    $stored = read_json('marketing.json', []);
    return array_replace_recursive(default_marketing(), is_array($stored) ? $stored : []);
}

function save_marketing(array $data): bool {
    return write_json('marketing.json', $data);
}

/* ------------------------------------------------------------------ */
/* Validation helpers                                                  */
/* ------------------------------------------------------------------ */

/** Returns normalized ID, '' for empty input, or null when the format is invalid. */
function marketing_clean_ga4(string $value): ?string {
    $value = strtoupper(trim($value));
    if ($value === '') return '';
    return preg_match('/^G-[A-Z0-9]{4,15}$/', $value) ? $value : null;
}

function marketing_clean_gtm(string $value): ?string {
    $value = strtoupper(trim($value));
    if ($value === '') return '';
    return preg_match('/^GTM-[A-Z0-9]{4,12}$/', $value) ? $value : null;
}

function marketing_clean_pixel(string $value): ?string {
    $value = preg_replace('/\s+/', '', $value) ?? '';
    if ($value === '') return '';
    return preg_match('/^\d{10,20}$/', $value) ? $value : null;
}

/**
 * Banner link: #anchor, /relative/path or https://… (http:// allowed off-demo).
 * In demo mode only on-site links are allowed (no external spam links on the public demo).
 */
function marketing_clean_link(string $value, bool $demo = false): ?string {
    $value = trim($value);
    if ($value === '') return '';
    if (preg_match('/[\x00-\x1F\x7F<>"\'`\s]/', $value)) return null;
    if ($value[0] === '#') {
        return preg_match('/^#[A-Za-z0-9_\-]{1,60}$/', $value) ? $value : null;
    }
    if ($value[0] === '/' && !str_starts_with($value, '//')) {
        return mb_substr($value, 0, 300);
    }
    if ($demo) {
        $siteHost = (string) parse_url(SITE_URL, PHP_URL_HOST);
        $host = (string) parse_url($value, PHP_URL_HOST);
        if ($siteHost === '' || strcasecmp($siteHost, $host) !== 0) return null;
    }
    if (preg_match('#^https?://[A-Za-z0-9.\-]+(:\d+)?(/.*)?$#', $value) && filter_var($value, FILTER_VALIDATE_URL)) {
        return mb_substr($value, 0, 300);
    }
    return null;
}

/** Y-m-d or '' — null when invalid. */
function marketing_clean_date(string $value): ?string {
    $value = trim($value);
    if ($value === '') return '';
    $d = DateTime::createFromFormat('!Y-m-d', $value);
    return ($d && $d->format('Y-m-d') === $value) ? $value : null;
}

function marketing_clean_text(string $value, int $max): string {
    $value = preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $value) ?? '';
    return mb_substr(trim($value), 0, $max);
}

/* ------------------------------------------------------------------ */
/* Promo banner                                                        */
/* ------------------------------------------------------------------ */

/** 'active' | 'disabled' | 'scheduled' | 'expired' | 'empty' */
function marketing_banner_state(array $banner, string $lang = 'no', ?string $today = null): string {
    if (empty($banner['enabled'])) return 'disabled';
    $today = $today ?? date('Y-m-d');
    if (!empty($banner['starts_at']) && $today < $banner['starts_at']) return 'scheduled';
    if (!empty($banner['ends_at']) && $today > $banner['ends_at']) return 'expired';
    if (marketing_banner_text($banner, $lang)['text'] === '') return 'empty';
    return 'active';
}

/** Text + CTA for a language with fallback: lang → en → no. */
function marketing_banner_text(array $banner, string $lang): array {
    foreach ([$lang, 'en', 'no'] as $lc) {
        $text = trim((string) ($banner['text_' . $lc] ?? ''));
        if ($text !== '') {
            return ['text' => $text, 'cta' => trim((string) ($banner['cta_' . $lc] ?? ''))];
        }
    }
    return ['text' => '', 'cta' => ''];
}

/** Version key: changes whenever the banner content changes, so a closed banner re-appears for a new promo. */
function marketing_banner_version(array $banner): string {
    $parts = [$banner['starts_at'] ?? '', $banner['ends_at'] ?? '', $banner['link'] ?? ''];
    foreach (marketing_langs() as $lc) {
        $parts[] = $banner['text_' . $lc] ?? '';
    }
    return substr(md5(implode('|', $parts)), 0, 10);
}

/** Resolve on-site banner links against BASE_PATH (#anchors and absolute URLs stay as is). */
function marketing_banner_href(string $link): string {
    if ($link === '') return '';
    if ($link[0] === '#') return $link;
    if ($link[0] === '/') {
        if (BASE_PATH !== '' && str_starts_with($link, BASE_PATH . '/')) return $link;
        return url_path(ltrim($link, '/'));
    }
    return $link;
}

/* ------------------------------------------------------------------ */
/* Public site output (called from render_popups on every public page) */
/* ------------------------------------------------------------------ */

function render_marketing(array $t, string $lang): void {
    $m = get_marketing();
    $banner = $m['banner'];
    $dirPrefix = rtrim((string) ($t['dir_prefix'] ?? ''), '/');

    $config = [
        'ga4' => $m['ga4_id'],
        'gtm' => $m['gtm_id'],
        'pixel' => $m['meta_pixel_id'],
        'requireConsent' => !empty($m['require_consent']),
        'trackLeads' => !empty($m['track_leads']),
        'trackClicks' => !empty($m['track_clicks']),
        'attributionDays' => max(1, min(365, (int) $m['attribution_days'])),
        'lang' => $lang,
    ];

    if (marketing_banner_state($banner, $lang) === 'active') {
        $copy = marketing_banner_text($banner, $lang);
        $href = marketing_banner_href((string) $banner['link']);
        $style = in_array($banner['style'], MARKETING_BANNER_STYLES, true) ? $banner['style'] : 'accent';
        $external = $href !== '' && preg_match('#^https?://#', $href) && !str_starts_with($href, SITE_URL);
        $config['bannerVersion'] = marketing_banner_version($banner);
        // #anchor missing on the current page (e.g. #contact on category pages) → go to the language home page
        $fallback = ($href !== '' && $href[0] === '#') ? site_href(($dirPrefix !== '' ? $dirPrefix : '') . '/') . $href : '';
        ?>
    <div class="ds-promo ds-promo-<?= htmlspecialchars($style) ?>" id="dsPromo" role="region" aria-label="Promo" hidden>
      <div class="ds-promo-inner">
        <span class="ds-promo-text"><?= htmlspecialchars($copy['text']) ?></span>
        <?php if ($href !== '' && $copy['cta'] !== ''): ?>
        <a class="ds-promo-cta" id="dsPromoCta" href="<?= htmlspecialchars($href) ?>"<?= $fallback !== '' ? ' data-fallback="' . htmlspecialchars($fallback) . '"' : '' ?><?= $external ? ' target="_blank" rel="noopener"' : '' ?>><?= htmlspecialchars($copy['cta']) ?></a>
        <?php endif; ?>
        <?php if (!empty($banner['dismissible'])): ?>
        <button type="button" class="ds-promo-close" id="dsPromoClose" aria-label="Close">&times;</button>
        <?php endif; ?>
      </div>
    </div>
        <?php
    }
    ?>
    <script>window.DS_MARKETING = <?= json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;</script>
    <link rel="stylesheet" href="<?= asset_url('/assets/css/marketing.css') ?>">
    <script src="<?= asset_url('/assets/js/marketing.js') ?>" defer></script>
    <?php
}

/* ------------------------------------------------------------------ */
/* Lead attribution (api/contact.php)                                  */
/* ------------------------------------------------------------------ */

function marketing_clean_param(string $value, int $max = 100): string {
    $value = trim($value);
    $value = preg_replace('/[^\p{L}\p{N}\s._\-+\/:|#@()]/u', '', $value) ?? '';
    $value = preg_replace('/\s+/u', ' ', $value) ?? '';
    return mb_substr(trim($value), 0, $max);
}

/**
 * Builds attribution data from hidden "mk_*" fields that assets/js/marketing.js adds to lead forms.
 * Only host of the external referrer and path of the landing page are stored (no query strings / personal data).
 */
function marketing_attribution_from_post(array $post): array {
    $out = [];
    foreach (MARKETING_UTM_KEYS as $key) {
        $v = marketing_clean_param((string) ($post['mk_' . $key] ?? ''));
        if ($v !== '') $out[$key] = $v;
    }

    $ref = trim((string) ($post['mk_referrer'] ?? ''));
    if ($ref !== '') {
        $host = parse_url($ref, PHP_URL_HOST);
        if (!is_string($host) && preg_match('/^[A-Za-z0-9.\-]+$/', $ref)) {
            $host = $ref;
        }
        if (is_string($host) && $host !== '') {
            $host = strtolower(preg_replace('/^www\./i', '', $host) ?? $host);
            $siteHost = strtolower((string) parse_url(SITE_URL, PHP_URL_HOST));
            if ($host !== $siteHost && preg_match('/^[a-z0-9.\-]{3,120}$/', $host)) {
                $out['referrer'] = $host;
            }
        }
    }

    $landing = trim((string) ($post['mk_landing'] ?? ''));
    if ($landing !== '') {
        $path = parse_url($landing, PHP_URL_PATH);
        if (is_string($path) && $path !== '' && $path[0] === '/') {
            $out['landing_page'] = marketing_page_path(mb_substr(preg_replace('/[^A-Za-z0-9._\-\/~%]/', '', $path) ?? '', 0, 200));
        }
    }

    $clickType = (string) ($post['mk_click_type'] ?? '');
    $clickId = preg_replace('/[^A-Za-z0-9_\-.]/', '', (string) ($post['mk_click_id'] ?? '')) ?? '';
    if (in_array($clickType, ['gclid', 'fbclid', 'msclkid', 'ttclid'], true) && $clickId !== '') {
        $out['click_type'] = $clickType;
        $out['click_id'] = substr($clickId, 0, 200);
    }

    $firstSeen = (int) ($post['mk_first_seen'] ?? 0);
    if ($firstSeen > 1500000000 && $firstSeen <= time() + 300) {
        $out['first_seen'] = date('Y-m-d H:i:s', $firstSeen);
    }

    $out['channel'] = marketing_channel(['marketing' => $out]);
    return $out;
}

/** Site-relative page path: strips BASE_PATH so "/driving-school/cms/en/" and "/en/" are the same page. */
function marketing_page_path(string $path): string {
    if ($path === '') return '';
    $base = rtrim(BASE_PATH, '/');
    if ($base !== '' && ($path === $base || str_starts_with($path, $base . '/'))) {
        $path = substr($path, strlen($base));
    }
    return $path === '' ? '/' : $path;
}

/** Channel classification for an order. Orders created before the module have no "marketing" key → unknown. */
function marketing_channel(array $order): string {
    if (!isset($order['marketing']) || !is_array($order['marketing'])) {
        return 'unknown';
    }
    $mk = $order['marketing'];
    $source = strtolower((string) ($mk['utm_source'] ?? ''));
    $medium = strtolower((string) ($mk['utm_medium'] ?? ''));
    $ref = strtolower((string) ($mk['referrer'] ?? ''));
    $click = (string) ($mk['click_type'] ?? '');

    $socialRe = '/(facebook|instagram|^fb$|^ig$|meta|tiktok|linkedin|snapchat|youtube|twitter|^x$|t\.co|pinterest|vk\.com|telegram|whatsapp)/';
    $searchRe = '/(^|\.)(google|bing|duckduckgo|yahoo|yandex|ecosia|baidu|startpage|kvasir|seznam)\./';

    if (in_array($medium, ['cpc', 'ppc', 'paid', 'paidsocial', 'paid_social', 'paid-social', 'display', 'banner', 'cpm', 'retargeting'], true)
        || in_array($click, ['gclid', 'msclkid'], true)) {
        return 'paid';
    }
    if (in_array($medium, ['email', 'e-mail', 'newsletter', 'mail'], true)) {
        return 'email';
    }
    if ($medium === 'social' || in_array($click, ['fbclid', 'ttclid'], true) || ($source !== '' && preg_match($socialRe, $source)) || ($ref !== '' && preg_match($socialRe, $ref))) {
        return 'social';
    }
    if ($medium === 'organic' || ($source === '' && $ref !== '' && preg_match($searchRe, $ref . '.'))) {
        return 'organic';
    }
    if ($source !== '' || $medium !== '' || !empty($mk['utm_campaign'])) {
        return 'campaign';
    }
    if ($ref !== '') {
        return 'referral';
    }
    return 'direct';
}

/** Human readable source: utm_source / referrer host / (direct). */
function marketing_source_label(array $order): string {
    $mk = is_array($order['marketing'] ?? null) ? $order['marketing'] : [];
    if (!empty($mk['utm_source'])) {
        return $mk['utm_source'] . (!empty($mk['utm_medium']) ? ' / ' . $mk['utm_medium'] : '');
    }
    if (!empty($mk['referrer'])) return $mk['referrer'];
    if (!empty($mk['click_type'])) return $mk['click_type'];
    return isset($order['marketing']) ? '(direct)' : '—';
}

/* ------------------------------------------------------------------ */
/* Statistics                                                          */
/* ------------------------------------------------------------------ */

/**
 * @param int $days 0 = all time
 */
function marketing_filter_orders(array $orders, int $days): array {
    if ($days <= 0) return array_values($orders);
    $from = date('Y-m-d 00:00:00', strtotime('-' . ($days - 1) . ' days'));
    return array_values(array_filter($orders, fn($o) => (string) ($o['created_at'] ?? '') >= $from));
}

function marketing_stats(array $orders, int $days): array {
    $list = marketing_filter_orders($orders, $days);

    $group = static function (array $rows, callable $key): array {
        $out = [];
        foreach ($rows as $o) {
            $k = (string) $key($o);
            if ($k === '') $k = '—';
            if (!isset($out[$k])) $out[$k] = ['leads' => 0, 'done' => 0];
            $out[$k]['leads']++;
            if (($o['status'] ?? '') === 'done') $out[$k]['done']++;
        }
        uasort($out, fn($a, $b) => $b['leads'] <=> $a['leads']);
        return $out;
    };

    $total = count($list);
    $done = count(array_filter($list, fn($o) => ($o['status'] ?? '') === 'done'));
    $contacted = count(array_filter($list, fn($o) => ($o['status'] ?? '') === 'contacted'));
    $tracked = count(array_filter($list, fn($o) => is_array($o['marketing'] ?? null)
        && (count(array_diff(array_keys($o['marketing']), ['channel', 'first_seen'])) > 0)));

    // Daily series (max 90 bars for readability)
    $span = $days > 0 ? min($days, 90) : 30;
    if ($days <= 0 && $total > 0) {
        $firstDate = min(array_map(fn($o) => substr((string) ($o['created_at'] ?? date('Y-m-d')), 0, 10), $list));
        $span = (int) min(90, max(7, (int) floor((time() - strtotime($firstDate)) / 86400) + 1));
    }
    $daily = [];
    for ($i = $span - 1; $i >= 0; $i--) {
        $daily[date('Y-m-d', strtotime('-' . $i . ' days'))] = 0;
    }
    foreach ($list as $o) {
        $d = substr((string) ($o['created_at'] ?? ''), 0, 10);
        if (isset($daily[$d])) $daily[$d]++;
    }

    $periodDays = $days > 0 ? $days : max(1, $span);

    return [
        'total' => $total,
        'done' => $done,
        'contacted' => $contacted,
        'new' => $total - $done - $contacted,
        'tracked' => $tracked,
        'tracked_pct' => $total > 0 ? round($tracked * 100 / $total) : 0,
        'conversion_pct' => $total > 0 ? round($done * 100 / $total, 1) : 0,
        'per_day' => round($total / $periodDays, 2),
        'daily' => $daily,
        'by_channel' => $group($list, fn($o) => marketing_channel($o)),
        'by_source' => $group($list, fn($o) => marketing_source_label($o)),
        'by_campaign' => $group(array_filter($list, fn($o) => !empty($o['marketing']['utm_campaign'])), fn($o) => $o['marketing']['utm_campaign']),
        'by_category' => $group($list, fn($o) => $o['category'] ?? ''),
        'by_page' => $group($list, fn($o) => marketing_page_path((string) (($o['marketing']['landing_page'] ?? '') ?: ($o['source_page'] ?? '')))),
        'by_lang' => $group($list, fn($o) => strtoupper((string) ($o['lang'] ?? ''))),
    ];
}

/* ------------------------------------------------------------------ */
/* CSV export                                                          */
/* ------------------------------------------------------------------ */

function marketing_csv_cell(string $value): string {
    // Prevent CSV/formula injection in Excel / Google Sheets
    if ($value !== '' && strpbrk($value[0], "=+-@\t\r") !== false) {
        $value = "'" . $value;
    }
    return $value;
}

function marketing_send_csv(array $orders, string $filename): void {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . preg_replace('/[^A-Za-z0-9._\-]/', '', $filename) . '"');
    header('Cache-Control: no-store');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
    $cols = ['id', 'created_at', 'status', 'name', 'phone', 'email', 'category', 'lang', 'source_page',
        'channel', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
        'referrer', 'landing_page', 'click_type', 'first_seen'];
    fputcsv($out, $cols, ';');
    foreach ($orders as $o) {
        $mk = is_array($o['marketing'] ?? null) ? $o['marketing'] : [];
        $row = [
            (string) ($o['id'] ?? ''), (string) ($o['created_at'] ?? ''), (string) ($o['status'] ?? ''),
            (string) ($o['name'] ?? ''), (string) ($o['phone'] ?? ''), (string) ($o['email'] ?? ''),
            (string) ($o['category'] ?? ''), (string) ($o['lang'] ?? ''), (string) ($o['source_page'] ?? ''),
            marketing_channel($o),
        ];
        foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'referrer', 'landing_page', 'click_type', 'first_seen'] as $k) {
            $row[] = (string) ($mk[$k] ?? '');
        }
        foreach ($row as $i => $cell) {
            // phone (index 4) is already restricted to digits, +, (), - and spaces
            if ($i !== 4) $row[$i] = marketing_csv_cell($cell);
        }
        fputcsv($out, $row, ';');
    }
    fclose($out);
}
