<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/store.php';
require_once __DIR__ . '/../inc/settings.php';
require_once __DIR__ . '/../inc/activity_log.php';
require_once __DIR__ . '/../inc/languages.php';
require_once __DIR__ . '/../inc/categories.php';
require_once __DIR__ . '/../inc/marketing.php';
require_once __DIR__ . '/../inc/admin_layout.php';

require_login();

$demo = ds_demo_mode();
$tabs = [
    'overview' => at('mk_tab_overview'),
    'tracking' => at('mk_tab_tracking'),
    'banner' => at('mk_tab_banner'),
    'links' => at('mk_tab_links'),
];
$tab = (string) ($_GET['tab'] ?? 'overview');
if (!isset($tabs[$tab])) $tab = 'overview';

$periods = [7, 30, 90, 365, 0];
$days = (int) ($_GET['days'] ?? 30);
if (!in_array($days, $periods, true)) $days = 30;

$languages = site_languages();

/* ---------- CSV export ---------- */
if (($_GET['export'] ?? '') === 'csv') {
    $orders = read_json('orders.json', []);
    if (!is_array($orders)) $orders = [];
    $list = marketing_filter_orders($orders, $days);
    usort($list, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
    log_activity('marketing_export', 'days=' . $days . ', rows=' . count($list));
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    marketing_send_csv($list, 'leads-' . ($days > 0 ? $days . 'd-' : 'all-') . date('Ymd') . '.csv');
    exit;
}

/* ---------- Save forms ---------- */
$notice = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = (string) ($_POST['form'] ?? '');
    if (!csrf_check($_POST['csrf'] ?? null)) {
        $notice = 'err:' . at('session_expired');
    } elseif ($form === 'tracking') {
        $tab = 'tracking';
        if ($demo) {
            $notice = 'err:' . at('mk_demo_tracking');
        } else {
            $m = get_marketing();
            $ga4 = marketing_clean_ga4((string) ($_POST['ga4_id'] ?? ''));
            $gtm = marketing_clean_gtm((string) ($_POST['gtm_id'] ?? ''));
            $pixel = marketing_clean_pixel((string) ($_POST['meta_pixel_id'] ?? ''));
            if ($ga4 === null) $errors[] = sprintf(at('mk_invalid_id'), 'Google Analytics 4 (G-XXXXXXXXXX)');
            if ($gtm === null) $errors[] = sprintf(at('mk_invalid_id'), 'Google Tag Manager (GTM-XXXXXXX)');
            if ($pixel === null) $errors[] = sprintf(at('mk_invalid_id'), 'Meta Pixel (10–20 digits)');
            if (!$errors) {
                $m['ga4_id'] = $ga4;
                $m['gtm_id'] = $gtm;
                $m['meta_pixel_id'] = $pixel;
                $m['require_consent'] = !empty($_POST['require_consent']);
                $m['track_leads'] = !empty($_POST['track_leads']);
                $m['track_clicks'] = !empty($_POST['track_clicks']);
                $m['attribution_days'] = max(1, min(365, (int) ($_POST['attribution_days'] ?? 90)));
                save_marketing($m);
                log_activity('marketing_save', 'section=tracking');
                $notice = 'ok:' . at('mk_saved');
            }
        }
    } elseif ($form === 'banner') {
        $tab = 'banner';
        $m = get_marketing();
        $b = $m['banner'];
        $link = marketing_clean_link((string) ($_POST['link'] ?? ''), $demo);
        $starts = marketing_clean_date((string) ($_POST['starts_at'] ?? ''));
        $ends = marketing_clean_date((string) ($_POST['ends_at'] ?? ''));
        if ($link === null) $errors[] = at('mk_banner_link_invalid');
        if ($starts === null || $ends === null) $errors[] = at('mk_banner_date_invalid');
        if ($starts && $ends && $ends < $starts) $errors[] = at('mk_banner_date_order');

        $b['enabled'] = !empty($_POST['enabled']);
        $b['dismissible'] = !empty($_POST['dismissible']);
        $style = (string) ($_POST['style'] ?? 'accent');
        $b['style'] = in_array($style, MARKETING_BANNER_STYLES, true) ? $style : 'accent';
        foreach (marketing_langs() as $lc) {
            $b['text_' . $lc] = marketing_clean_text((string) ($_POST['text_' . $lc] ?? ''), 220);
            $b['cta_' . $lc] = marketing_clean_text((string) ($_POST['cta_' . $lc] ?? ''), 40);
        }
        if (!$errors) {
            $b['link'] = $link;
            $b['starts_at'] = $starts;
            $b['ends_at'] = $ends;
            $m['banner'] = $b;
            save_marketing($m);
            log_activity('marketing_save', 'section=banner, enabled=' . ($b['enabled'] ? '1' : '0'));
            $notice = 'ok:' . at('mk_saved');
        }
    }
    if ($errors) {
        $notice = 'err:' . implode(' ', $errors);
    }
}

$m = get_marketing();
$csrf = csrf_token();

/* ---------- Helpers for the view ---------- */
$channelLabels = [];
foreach (MARKETING_CHANNELS as $ch) {
    $channelLabels[$ch] = at('mk_channel_' . $ch);
}
$channelColors = [
    'paid' => '#2f6fed', 'social' => '#8b5cf6', 'organic' => '#1c9a5b', 'email' => '#0ea5b7',
    'referral' => '#e2960a', 'campaign' => '#ec4899', 'direct' => '#64748b', 'unknown' => '#cbd5e1',
];

function mk_url(array $params): string {
    $q = array_merge(['tab' => $_GET['tab'] ?? 'overview', 'days' => $_GET['days'] ?? 30], $params);
    return '?' . http_build_query($q);
}

function mk_breakdown_table(array $rows, int $total, ?array $labels = null, int $limit = 10, ?array $colors = null): void {
    if (!$rows) {
        echo '<p class="muted">' . htmlspecialchars(at('mk_no_data')) . '</p>';
        return;
    }
    $max = max(array_column($rows, 'leads')) ?: 1;
    echo '<table class="mk-table"><thead><tr><th>' . htmlspecialchars(at('mk_col_name')) . '</th><th>' . htmlspecialchars(at('mk_col_leads'))
        . '</th><th>' . htmlspecialchars(at('mk_col_done')) . '</th><th>' . htmlspecialchars(at('mk_col_share')) . '</th></tr></thead><tbody>';
    foreach (array_slice($rows, 0, $limit, true) as $name => $r) {
        $label = $labels[$name] ?? (string) $name;
        $share = $total > 0 ? round($r['leads'] * 100 / $total) : 0;
        $color = $colors[$name] ?? 'var(--primary)';
        echo '<tr>'
            . '<td data-label="' . htmlspecialchars(at('mk_col_name')) . '"><span class="mk-name">' . htmlspecialchars($label) . '</span>'
            . '<span class="mk-bar"><span style="width:' . round($r['leads'] * 100 / $max) . '%;background:' . htmlspecialchars($color) . '"></span></span></td>'
            . '<td data-label="' . htmlspecialchars(at('mk_col_leads')) . '"><strong>' . (int) $r['leads'] . '</strong></td>'
            . '<td data-label="' . htmlspecialchars(at('mk_col_done')) . '">' . (int) $r['done'] . '</td>'
            . '<td data-label="' . htmlspecialchars(at('mk_col_share')) . '">' . $share . '%</td>'
            . '</tr>';
    }
    echo '</tbody></table>';
}

admin_header(at('mk_title'), 'marketing');
?>
<div class="mk-head">
  <h1><?= htmlspecialchars(at('mk_title')) ?></h1>
  <nav class="mk-tabs" aria-label="<?= htmlspecialchars(at('mk_title')) ?>">
    <?php foreach ($tabs as $code => $label): ?>
      <a href="?tab=<?= $code ?>&amp;days=<?= $days ?>" class="<?= $code === $tab ? 'active' : '' ?>"><?= htmlspecialchars($label) ?></a>
    <?php endforeach; ?>
  </nav>
</div>

<?php if ($notice): [$type, $text] = explode(':', $notice, 2); ?>
  <div class="alert alert-<?= $type === 'ok' ? 'ok' : 'err' ?>"><?= htmlspecialchars($text) ?></div>
<?php endif; ?>

<?php if ($tab === 'overview'):
    $orders = read_json('orders.json', []);
    if (!is_array($orders)) $orders = [];
    $st = marketing_stats($orders, $days);
    $dailyMax = max($st['daily'] ?: [0]) ?: 1;
    $dailyKeys = array_keys($st['daily']);
    $recent = marketing_filter_orders($orders, $days);
    usort($recent, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
    $recent = array_slice($recent, 0, 10);
    $statusLabels = ['new' => at('orders_status_new'), 'contacted' => at('orders_status_contacted'), 'done' => at('orders_status_done')];
?>
<div class="admin-card mk-toolbar">
  <div class="mk-pills">
    <?php foreach ($periods as $p): ?>
      <a href="<?= htmlspecialchars(mk_url(['days' => $p, 'tab' => 'overview'])) ?>" class="<?= $p === $days ? 'active' : '' ?>">
        <?= htmlspecialchars($p > 0 ? sprintf(at('mk_period_days'), $p) : at('mk_period_all')) ?>
      </a>
    <?php endforeach; ?>
  </div>
  <a class="btn btn-outline btn-sm" href="<?= htmlspecialchars(mk_url(['export' => 'csv', 'days' => $days])) ?>">⬇ <?= htmlspecialchars(at('mk_export_csv')) ?></a>
</div>

<div class="mk-kpis">
  <div class="mk-kpi">
    <span class="mk-kpi-label"><?= htmlspecialchars(at('mk_kpi_leads')) ?></span>
    <strong><?= (int) $st['total'] ?></strong>
    <span class="muted"><?= htmlspecialchars(at('mk_kpi_per_day')) ?>: <?= htmlspecialchars((string) $st['per_day']) ?></span>
  </div>
  <div class="mk-kpi">
    <span class="mk-kpi-label"><?= htmlspecialchars(at('mk_kpi_conversion')) ?></span>
    <strong><?= htmlspecialchars((string) $st['conversion_pct']) ?>%</strong>
    <span class="muted"><?= htmlspecialchars(at('orders_status_done')) ?>: <?= (int) $st['done'] ?></span>
  </div>
  <div class="mk-kpi">
    <span class="mk-kpi-label"><?= htmlspecialchars(at('mk_kpi_tracked')) ?></span>
    <strong><?= (int) $st['tracked_pct'] ?>%</strong>
    <span class="muted"><?= (int) $st['tracked'] ?> / <?= (int) $st['total'] ?></span>
  </div>
  <div class="mk-kpi">
    <span class="mk-kpi-label"><?= htmlspecialchars(at('mk_kpi_new')) ?></span>
    <strong><?= (int) $st['new'] ?></strong>
    <span class="muted"><a href="<?= BASE_PATH ?>/admin/orders.php"><?= htmlspecialchars(at('nav_orders')) ?> →</a></span>
  </div>
</div>

<div class="admin-card">
  <h2><?= htmlspecialchars(at('mk_chart_title')) ?></h2>
  <?php if ($st['total'] === 0): ?>
    <p class="muted"><?= htmlspecialchars(at('mk_no_data')) ?></p>
  <?php endif; ?>
  <div class="mk-chart" role="img" aria-label="<?= htmlspecialchars(at('mk_chart_title')) ?>">
    <?php foreach ($st['daily'] as $date => $count): ?>
      <span class="mk-col" title="<?= htmlspecialchars($date . ': ' . $count) ?>"><span style="height:<?= $count > 0 ? max(4, round($count * 100 / $dailyMax)) : 0 ?>%"></span></span>
    <?php endforeach; ?>
  </div>
  <?php if ($dailyKeys): ?>
  <div class="mk-chart-axis">
    <span><?= htmlspecialchars(date('d.m', strtotime($dailyKeys[0]))) ?></span>
    <span><?= htmlspecialchars(date('d.m', strtotime($dailyKeys[(int) floor(count($dailyKeys) / 2)]))) ?></span>
    <span><?= htmlspecialchars(date('d.m', strtotime(end($dailyKeys)))) ?></span>
  </div>
  <?php endif; ?>
  <div class="mk-funnel">
    <?php foreach (['new' => $st['new'], 'contacted' => $st['contacted'], 'done' => $st['done']] as $sk => $sv): ?>
      <div class="mk-funnel-step mk-funnel-<?= $sk ?>">
        <span><?= htmlspecialchars($statusLabels[$sk]) ?></span>
        <strong><?= (int) $sv ?></strong>
        <em><?= $st['total'] > 0 ? round($sv * 100 / $st['total']) : 0 ?>%</em>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php if ($st['total'] > 0 && $st['tracked'] === 0): ?>
  <div class="alert alert-ok">💡 <?= htmlspecialchars(at('mk_tip_utm')) ?> <a href="?tab=links" style="text-decoration:underline"><?= htmlspecialchars(at('mk_tab_links')) ?> →</a></div>
<?php endif; ?>

<div class="grid-2 mk-grid">
  <div class="admin-card">
    <h2><?= htmlspecialchars(at('mk_by_channel')) ?></h2>
    <?php mk_breakdown_table($st['by_channel'], $st['total'], $channelLabels, 10, $channelColors); ?>
  </div>
  <div class="admin-card">
    <h2><?= htmlspecialchars(at('mk_by_source')) ?></h2>
    <?php mk_breakdown_table($st['by_source'], $st['total']); ?>
  </div>
  <div class="admin-card">
    <h2><?= htmlspecialchars(at('mk_by_campaign')) ?></h2>
    <?php mk_breakdown_table($st['by_campaign'], $st['total']); ?>
  </div>
  <div class="admin-card">
    <h2><?= htmlspecialchars(at('mk_by_category')) ?></h2>
    <?php mk_breakdown_table($st['by_category'], $st['total']); ?>
  </div>
  <div class="admin-card">
    <h2><?= htmlspecialchars(at('mk_by_page')) ?></h2>
    <?php mk_breakdown_table($st['by_page'], $st['total']); ?>
  </div>
  <div class="admin-card">
    <h2><?= htmlspecialchars(at('mk_by_lang')) ?></h2>
    <?php mk_breakdown_table($st['by_lang'], $st['total']); ?>
  </div>
</div>

<div class="admin-card">
  <h2><?= htmlspecialchars(at('mk_recent_title')) ?></h2>
  <?php if (!$recent): ?>
    <p class="muted"><?= htmlspecialchars(at('mk_no_data')) ?></p>
  <?php else: ?>
  <table class="mk-table">
    <thead><tr>
      <th><?= htmlspecialchars(at('payment_col_date', 'Date')) ?></th>
      <th><?= htmlspecialchars(at('mk_col_name')) ?></th>
      <th><?= htmlspecialchars(at('mk_by_channel')) ?></th>
      <th><?= htmlspecialchars(at('mk_col_source')) ?></th>
      <th><?= htmlspecialchars(at('mk_col_campaign')) ?></th>
      <th><?= htmlspecialchars(at('mk_col_landing')) ?></th>
    </tr></thead>
    <tbody>
      <?php foreach ($recent as $o): $ch = marketing_channel($o); $mk = is_array($o['marketing'] ?? null) ? $o['marketing'] : []; ?>
      <tr>
        <td data-label="<?= htmlspecialchars(at('payment_col_date', 'Date')) ?>"><?= htmlspecialchars((string) ($o['created_at'] ?? '')) ?></td>
        <td data-label="<?= htmlspecialchars(at('mk_col_name')) ?>"><strong><?= htmlspecialchars((string) ($o['name'] ?? '')) ?></strong> <span class="chip"><?= htmlspecialchars((string) ($o['category'] ?? '')) ?></span></td>
        <td data-label="<?= htmlspecialchars(at('mk_by_channel')) ?>"><span class="mk-dot" style="background:<?= $channelColors[$ch] ?>"></span><?= htmlspecialchars($channelLabels[$ch]) ?></td>
        <td data-label="<?= htmlspecialchars(at('mk_col_source')) ?>"><?= htmlspecialchars(marketing_source_label($o)) ?></td>
        <td data-label="<?= htmlspecialchars(at('mk_col_campaign')) ?>"><?= htmlspecialchars((string) ($mk['utm_campaign'] ?? '—')) ?></td>
        <td data-label="<?= htmlspecialchars(at('mk_col_landing')) ?>"><?= htmlspecialchars(marketing_page_path((string) (($mk['landing_page'] ?? '') ?: ($o['source_page'] ?? ''))) ?: '—') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php elseif ($tab === 'tracking'): ?>
<form method="post" class="admin-card">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
  <input type="hidden" name="form" value="tracking">
  <h2><?= htmlspecialchars(at('mk_tracking_title')) ?></h2>
  <p class="muted"><?= htmlspecialchars(at('mk_tracking_note')) ?></p>
  <?php if ($demo): ?>
    <div class="alert alert-ok"><?= htmlspecialchars(at('mk_demo_tracking')) ?></div>
  <?php endif; ?>

  <div class="mk-status-row">
    <?php foreach (['GA4' => $m['ga4_id'], 'GTM' => $m['gtm_id'], 'Meta Pixel' => $m['meta_pixel_id']] as $name => $val): ?>
      <span class="chip <?= $val !== '' ? 'mk-chip-on' : 'chip-muted' ?>"><?= $val !== '' ? '●' : '○' ?> <?= htmlspecialchars($name) ?> — <?= htmlspecialchars($val !== '' ? at('mk_status_active') : at('mk_status_inactive')) ?></span>
    <?php endforeach; ?>
  </div>

  <div class="grid-3">
    <label><?= htmlspecialchars(at('mk_ga4')) ?>
      <input type="text" name="ga4_id" value="<?= htmlspecialchars($m['ga4_id']) ?>" placeholder="G-XXXXXXXXXX" autocomplete="off" spellcheck="false" <?= $demo ? 'disabled' : '' ?>>
    </label>
    <label><?= htmlspecialchars(at('mk_gtm')) ?>
      <input type="text" name="gtm_id" value="<?= htmlspecialchars($m['gtm_id']) ?>" placeholder="GTM-XXXXXXX" autocomplete="off" spellcheck="false" <?= $demo ? 'disabled' : '' ?>>
    </label>
    <label><?= htmlspecialchars(at('mk_meta')) ?>
      <input type="text" name="meta_pixel_id" value="<?= htmlspecialchars($m['meta_pixel_id']) ?>" placeholder="123456789012345" inputmode="numeric" autocomplete="off" <?= $demo ? 'disabled' : '' ?>>
    </label>
  </div>

  <label class="mk-check"><input type="checkbox" name="require_consent" <?= !empty($m['require_consent']) ? 'checked' : '' ?> <?= $demo ? 'disabled' : '' ?>><span><?= htmlspecialchars(at('mk_require_consent')) ?></span></label>
  <label class="mk-check"><input type="checkbox" name="track_leads" <?= !empty($m['track_leads']) ? 'checked' : '' ?> <?= $demo ? 'disabled' : '' ?>><span><?= htmlspecialchars(at('mk_track_leads')) ?></span></label>
  <label class="mk-check"><input type="checkbox" name="track_clicks" <?= !empty($m['track_clicks']) ? 'checked' : '' ?> <?= $demo ? 'disabled' : '' ?>><span><?= htmlspecialchars(at('mk_track_clicks')) ?></span></label>

  <div class="grid-3">
    <label><?= htmlspecialchars(at('mk_attribution_days')) ?>
      <input type="number" name="attribution_days" min="1" max="365" step="1" value="<?= (int) $m['attribution_days'] ?>" <?= $demo ? 'disabled' : '' ?>>
    </label>
  </div>

  <div class="mk-events">
    <h3><?= htmlspecialchars(at('mk_events_title')) ?></h3>
    <ul>
      <li><code>generate_lead</code> / Meta <code>Lead</code> — <?= htmlspecialchars(at('mk_event_lead')) ?></li>
      <li><code>contact_click</code> / Meta <code>Contact</code> — <?= htmlspecialchars(at('mk_event_click')) ?></li>
      <li><code>promo_click</code>, <code>promo_close</code> — <?= htmlspecialchars(at('mk_event_promo')) ?></li>
      <li><code>dataLayer</code>: <code>ds_generate_lead</code>, <code>ds_contact_click</code>, <code>ds_promo_click</code> — <?= htmlspecialchars(at('mk_event_gtm')) ?></li>
    </ul>
  </div>

  <?php if (!$demo): ?>
    <button type="submit" class="btn"><?= htmlspecialchars(at('settings_save')) ?></button>
  <?php endif; ?>
</form>

<?php elseif ($tab === 'banner'):
    $b = $m['banner'];
    // Keep submitted values on validation error
    if ($errors && ($_POST['form'] ?? '') === 'banner') {
        foreach (['link', 'starts_at', 'ends_at', 'style'] as $k) {
            $b[$k] = (string) ($_POST[$k] ?? $b[$k]);
        }
        $b['enabled'] = !empty($_POST['enabled']);
        $b['dismissible'] = !empty($_POST['dismissible']);
        foreach (marketing_langs() as $lc) {
            $b['text_' . $lc] = (string) ($_POST['text_' . $lc] ?? '');
            $b['cta_' . $lc] = (string) ($_POST['cta_' . $lc] ?? '');
        }
    }
    $state = marketing_banner_state($b, 'no');
    $stateLabels = [
        'active' => at('mk_status_active'), 'disabled' => at('mk_status_inactive'),
        'scheduled' => at('mk_banner_scheduled'), 'expired' => at('mk_banner_expired'), 'empty' => at('mk_banner_empty'),
    ];
    $preview = marketing_banner_text($b, 'no');
?>
<form method="post" class="admin-card" id="mkBannerForm">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
  <input type="hidden" name="form" value="banner">
  <h2><?= htmlspecialchars(at('mk_banner_title')) ?> <span class="chip <?= $state === 'active' ? 'mk-chip-on' : 'chip-muted' ?>"><?= htmlspecialchars($stateLabels[$state] ?? $state) ?></span></h2>
  <p class="muted"><?= htmlspecialchars(at('mk_banner_note')) ?></p>

  <div class="mk-preview-label"><?= htmlspecialchars(at('mk_banner_preview')) ?> (NO)</div>
  <div class="mk-preview mk-preview-<?= htmlspecialchars($b['style']) ?>" id="mkPreview">
    <span id="mkPreviewText"><?= htmlspecialchars($preview['text']) ?></span>
    <span class="mk-preview-cta" id="mkPreviewCta"<?= $preview['cta'] === '' ? ' hidden' : '' ?>><?= htmlspecialchars($preview['cta']) ?></span>
    <?php if (!empty($b['dismissible'])): ?><span class="mk-preview-close" id="mkPreviewClose">&times;</span><?php endif; ?>
  </div>

  <label class="mk-check"><input type="checkbox" name="enabled" <?= !empty($b['enabled']) ? 'checked' : '' ?>><span><?= htmlspecialchars(at('mk_banner_enabled')) ?></span></label>
  <label class="mk-check"><input type="checkbox" name="dismissible" id="mkDismissible" <?= !empty($b['dismissible']) ? 'checked' : '' ?>><span><?= htmlspecialchars(at('mk_banner_dismissible')) ?></span></label>

  <div class="grid-2">
    <label><?= htmlspecialchars(at('mk_banner_style')) ?>
      <select name="style" id="mkStyle">
        <?php foreach (MARKETING_BANNER_STYLES as $style): ?>
          <option value="<?= $style ?>" <?= $b['style'] === $style ? 'selected' : '' ?>><?= htmlspecialchars(at('mk_style_' . $style)) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label><?= htmlspecialchars(at('mk_banner_link')) ?>
      <input type="text" name="link" value="<?= htmlspecialchars((string) $b['link']) ?>" placeholder="#contact" autocomplete="off" spellcheck="false">
    </label>
    <label><?= htmlspecialchars(at('mk_banner_starts')) ?>
      <input type="date" name="starts_at" value="<?= htmlspecialchars((string) $b['starts_at']) ?>">
    </label>
    <label><?= htmlspecialchars(at('mk_banner_ends')) ?>
      <input type="date" name="ends_at" value="<?= htmlspecialchars((string) $b['ends_at']) ?>">
    </label>
  </div>
  <?php if ($demo): ?><p class="muted"><?= htmlspecialchars(at('mk_banner_demo_links')) ?></p><?php endif; ?>

  <hr class="section-divider">
  <?php foreach ($languages as $lc => $l): ?>
    <div class="mk-lang-row">
      <span class="chip"><?= htmlspecialchars($l['code']) ?></span>
      <label><?= htmlspecialchars(at('mk_banner_text')) ?>
        <input type="text" name="text_<?= $lc ?>" maxlength="220" value="<?= htmlspecialchars((string) ($b['text_' . $lc] ?? '')) ?>" <?= $lc === 'no' ? 'id="mkTextNo"' : '' ?>>
      </label>
      <label><?= htmlspecialchars(at('mk_banner_cta')) ?>
        <input type="text" name="cta_<?= $lc ?>" maxlength="40" value="<?= htmlspecialchars((string) ($b['cta_' . $lc] ?? '')) ?>" <?= $lc === 'no' ? 'id="mkCtaNo"' : '' ?>>
      </label>
    </div>
  <?php endforeach; ?>
  <p class="muted"><?= htmlspecialchars(at('mk_banner_fallback')) ?></p>

  <button type="submit" class="btn"><?= htmlspecialchars(at('settings_save')) ?></button>
</form>
<script>
(function(){
  var form = document.getElementById('mkBannerForm');
  if (!form) return;
  var preview = document.getElementById('mkPreview');
  var text = document.getElementById('mkPreviewText');
  var cta = document.getElementById('mkPreviewCta');
  var styleSel = document.getElementById('mkStyle');
  var textIn = document.getElementById('mkTextNo');
  var ctaIn = document.getElementById('mkCtaNo');
  function sync(){
    preview.className = 'mk-preview mk-preview-' + styleSel.value;
    text.textContent = textIn.value;
    cta.textContent = ctaIn.value;
    cta.hidden = ctaIn.value.trim() === '';
  }
  [styleSel, textIn, ctaIn].forEach(function(el){ el.addEventListener('input', sync); el.addEventListener('change', sync); });
})();
</script>

<?php elseif ($tab === 'links'):
    $pages = [];
    foreach ($languages as $lc => $l) {
        $pages[] = SITE_URL . $l['path'];
    }
    foreach (category_slugs() as $slug) {
        $pages[] = SITE_URL . '/kategorija-' . $slug . '.php';
    }
    $pages[] = SITE_URL . '/news.php';
    $pages[] = SITE_URL . '/gallery.php';
    $presets = [
        ['Facebook Ads', 'facebook', 'paid_social'],
        ['Instagram Ads', 'instagram', 'paid_social'],
        ['Google Ads', 'google', 'cpc'],
        ['TikTok Ads', 'tiktok', 'paid_social'],
        ['Snapchat Ads', 'snapchat', 'paid_social'],
        ['FINN.no', 'finn', 'display'],
        ['Newsletter', 'newsletter', 'email'],
        ['Flyer / QR', 'flyer', 'offline'],
        ['Facebook post', 'facebook', 'social'],
    ];
?>
<div class="admin-card" id="mkBuilder">
  <h2><?= htmlspecialchars(at('mk_links_title')) ?></h2>
  <p class="muted"><?= htmlspecialchars(at('mk_links_note')) ?></p>

  <div class="mk-presets">
    <span class="muted"><?= htmlspecialchars(at('mk_link_presets')) ?>:</span>
    <?php foreach ($presets as $p): ?>
      <button type="button" class="btn btn-outline btn-sm" data-source="<?= htmlspecialchars($p[1]) ?>" data-medium="<?= htmlspecialchars($p[2]) ?>"><?= htmlspecialchars($p[0]) ?></button>
    <?php endforeach; ?>
  </div>

  <div class="grid-2">
    <label class="mk-full"><?= htmlspecialchars(at('mk_link_base')) ?>
      <input type="url" id="mkBase" list="mkPages" value="<?= htmlspecialchars(SITE_URL . '/') ?>" spellcheck="false">
      <datalist id="mkPages">
        <?php foreach ($pages as $p): ?><option value="<?= htmlspecialchars($p) ?>"><?php endforeach; ?>
      </datalist>
    </label>
    <label><?= htmlspecialchars(at('mk_link_source')) ?> *<input type="text" id="mkSource" placeholder="facebook" spellcheck="false"></label>
    <label><?= htmlspecialchars(at('mk_link_medium')) ?> *<input type="text" id="mkMedium" placeholder="paid_social" spellcheck="false"></label>
    <label><?= htmlspecialchars(at('mk_link_campaign')) ?> *<input type="text" id="mkCampaign" placeholder="klasse-b-host-<?= date('Y') ?>" spellcheck="false"></label>
    <label><?= htmlspecialchars(at('mk_link_content')) ?><input type="text" id="mkContent" placeholder="video-1" spellcheck="false"></label>
    <label><?= htmlspecialchars(at('mk_link_term')) ?><input type="text" id="mkTerm" placeholder="kjøreskole oslo" spellcheck="false"></label>
  </div>

  <label><?= htmlspecialchars(at('mk_link_result')) ?>
    <textarea id="mkResult" rows="3" readonly></textarea>
  </label>
  <div class="mk-builder-actions">
    <button type="button" class="btn" id="mkCopy"><?= htmlspecialchars(at('mk_link_copy')) ?></button>
    <a class="btn btn-outline" id="mkOpen" href="#" target="_blank" rel="noopener"><?= htmlspecialchars(at('mk_link_open')) ?> ↗</a>
    <span class="muted" id="mkCopyStatus" role="status" aria-live="polite"></span>
  </div>
</div>

<div class="admin-card">
  <h2><?= htmlspecialchars(at('mk_links_tips_title')) ?></h2>
  <ul class="mk-tips">
    <li><?= htmlspecialchars(at('mk_links_tip_1')) ?></li>
    <li><?= htmlspecialchars(at('mk_links_tip_2')) ?></li>
    <li><?= htmlspecialchars(at('mk_links_tip_3')) ?></li>
  </ul>
</div>
<script>
(function(){
  var $ = function(id){ return document.getElementById(id); };
  var fields = { utm_source: $('mkSource'), utm_medium: $('mkMedium'), utm_campaign: $('mkCampaign'), utm_content: $('mkContent'), utm_term: $('mkTerm') };
  var base = $('mkBase'), result = $('mkResult'), open = $('mkOpen'), status = $('mkCopyStatus');
  var copiedText = <?= json_encode(at('mk_link_copied'), JSON_UNESCAPED_UNICODE) ?>;
  var requiredText = <?= json_encode(at('mk_link_required'), JSON_UNESCAPED_UNICODE) ?>;

  function slug(v){
    return String(v || '').trim().toLowerCase().replace(/\s+/g, '-').replace(/[^\p{L}\p{N}._\-+]/gu, '');
  }
  function build(){
    var url;
    try { url = new URL(base.value.trim()); } catch (e) { result.value = ''; open.removeAttribute('href'); return ''; }
    Object.keys(fields).forEach(function(k){
      var v = k === 'utm_term' ? String(fields[k].value || '').trim() : slug(fields[k].value);
      if (v) url.searchParams.set(k, v); else url.searchParams.delete(k);
    });
    result.value = url.toString();
    open.href = url.toString();
    return result.value;
  }
  [base].concat(Object.keys(fields).map(function(k){ return fields[k]; })).forEach(function(el){
    el.addEventListener('input', build);
  });
  document.querySelectorAll('.mk-presets [data-source]').forEach(function(btn){
    btn.addEventListener('click', function(){
      fields.utm_source.value = btn.getAttribute('data-source');
      fields.utm_medium.value = btn.getAttribute('data-medium');
      build();
      fields.utm_campaign.focus();
    });
  });
  $('mkCopy').addEventListener('click', function(){
    var link = build();
    if (!link || !fields.utm_source.value.trim() || !fields.utm_medium.value.trim() || !fields.utm_campaign.value.trim()) {
      status.textContent = requiredText;
      return;
    }
    var done = function(){ status.textContent = copiedText; setTimeout(function(){ status.textContent = ''; }, 2000); };
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(link).then(done, function(){ result.select(); document.execCommand('copy'); done(); });
    } else {
      result.select(); document.execCommand('copy'); done();
    }
  });
  build();
})();
</script>
<?php endif; ?>

<?php admin_footer(); ?>
