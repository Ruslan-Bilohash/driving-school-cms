<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/store.php';
require_once __DIR__ . '/../inc/settings.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/activity_log.php';
require_once __DIR__ . '/../inc/admin_layout.php';
require_once __DIR__ . '/../inc/bilohash_client.php';

function nok_to_eur_label(string $amountText): string {
    $num = (float) str_replace([' ', 'kr', ','], ['', '', '.'], $amountText);
    $eur = round($num * 0.0910, 2);
    return number_format($eur, 2, ',', '') . ' € (' . $amountText . ')';
}

require_login();

$notice = '';
$isDemo = defined('DS_DEMO_MODE') && DS_DEMO_MODE;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($isDemo) {
        $notice = 'err:Demo mode — billing login / passwords are hidden and not editable on the public portfolio demo.';
    } elseif (!csrf_check($_POST['csrf'] ?? null)) {
        $notice = 'err:' . at('session_expired');
    } else {
        $s = get_settings();
        $s['billing_portal_url'] = trim((string) ($_POST['billing_portal_url'] ?? $s['billing_portal_url']));
        $s['billing_service_name'] = trim((string) ($_POST['billing_service_name'] ?? $s['billing_service_name']));
        $s['billing_client_name'] = trim((string) ($_POST['billing_client_name'] ?? $s['billing_client_name']));
        $s['billing_client_email'] = trim((string) ($_POST['billing_client_email'] ?? $s['billing_client_email']));
        if (trim((string) ($_POST['billing_client_password'] ?? '')) !== '') {
            $s['billing_client_password'] = trim((string) $_POST['billing_client_password']);
        }
        save_settings($s);
        log_activity('payment_save', '');
        $notice = 'ok:' . at('payment_saved');
    }
}

$s = get_settings();
$unpaidInvoices = null;
if (!$isDemo) {
    $unpaidInvoices = bilohash_fetch_unpaid_invoices($s['billing_client_email'] ?? '', $s['billing_client_password'] ?? '');
}

admin_header(at('payment_title'), 'payment');
?>
<h1><?= htmlspecialchars(at('payment_title')) ?></h1>
<?php if ($notice): [$type, $text] = explode(':', $notice, 2); ?>
  <div class="alert alert-<?= $type === 'ok' ? 'ok' : 'err' ?>"><?= htmlspecialchars($text) ?></div>
<?php endif; ?>

<div class="admin-card">
  <h2><?= htmlspecialchars(at('payment_section_services')) ?></h2>
  <?php if ($isDemo): ?>
    <p><strong>Driving School CMS demo — bilohash.com</strong></p>
    <p class="muted">Portfolio demo — no real client billing account is linked.</p>
    <a class="btn" href="https://bilohash.com/client/" target="_blank" rel="noopener"><?= htmlspecialchars(at('payment_open_portal')) ?></a>
  <?php else: ?>
    <p><strong><?= htmlspecialchars($s['billing_service_name'] ?? '') ?></strong></p>
    <a class="btn" href="<?= htmlspecialchars($s['billing_portal_url'] ?? 'https://bilohash.com/client/') ?>" target="_blank" rel="noopener"><?= htmlspecialchars(at('payment_open_portal')) ?></a>
  <?php endif; ?>
</div>

<div class="admin-card">
  <h2><?= htmlspecialchars(at('payment_section_invoices')) ?></h2>
  <?php if ($isDemo): ?>
    <div class="alert alert-ok">✅ Demo mode — no real unpaid invoices. Client names, emails and passwords are not displayed.</div>
    <table>
      <thead><tr><th><?= htmlspecialchars(at('payment_col_number')) ?></th><th><?= htmlspecialchars(at('payment_col_amount')) ?></th><th><?= htmlspecialchars(at('payment_col_status')) ?></th><th><?= htmlspecialchars(at('payment_col_date')) ?></th></tr></thead>
      <tbody>
        <tr>
          <td>DS-DEMO-00001</td>
          <td>49,00 NOK</td>
          <td><span class="badge badge-new">Demo</span></td>
          <td>2026-07-01</td>
        </tr>
      </tbody>
    </table>
  <?php elseif ($unpaidInvoices === null): ?>
    <div class="alert alert-err"><?= htmlspecialchars(at('payment_fetch_error', 'Could not fetch invoices.')) ?></div>
  <?php elseif (empty($unpaidInvoices)): ?>
    <div class="alert alert-ok">✅ <?= htmlspecialchars(at('payment_all_good', 'No unpaid invoices.')) ?></div>
  <?php else: ?>
    <table>
      <thead><tr><th><?= htmlspecialchars(at('payment_col_number')) ?></th><th><?= htmlspecialchars(at('payment_col_amount')) ?></th><th><?= htmlspecialchars(at('payment_col_status')) ?></th><th><?= htmlspecialchars(at('payment_col_date')) ?></th></tr></thead>
      <tbody>
      <?php foreach ($unpaidInvoices as $inv): ?>
        <tr>
          <td><?= htmlspecialchars($inv['number'] ?? '') ?></td>
          <td><?= htmlspecialchars(nok_to_eur_label((string) ($inv['amount'] ?? ''))) ?></td>
          <td><span class="badge badge-new"><?= htmlspecialchars($inv['status'] ?? '') ?></span></td>
          <td><?= htmlspecialchars($inv['date'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
  <p class="muted" style="margin-top:10px"><?= htmlspecialchars(at('payment_invoices_note')) ?></p>
</div>

<?php if ($isDemo): ?>
<div class="admin-card">
  <h2><?= htmlspecialchars(at('payment_section_login')) ?> — demo</h2>
  <div class="alert alert-ok">Client name, email and password for bilohash.com/client are <strong>not shown</strong> on the public demo. Real installs store them server-side only.</div>
  <div class="grid-2">
    <label><?= htmlspecialchars(at('payment_portal_url')) ?><input type="text" value="https://bilohash.com/client/" disabled></label>
    <label><?= htmlspecialchars(at('payment_service_name')) ?><input type="text" value="Driving School CMS demo" disabled></label>
    <label><?= htmlspecialchars(at('payment_client_name')) ?><input type="text" value="••••••••" disabled autocomplete="off"></label>
    <label><?= htmlspecialchars(at('payment_client_email')) ?><input type="text" value="••••••••@••••.•••" disabled autocomplete="off"></label>
  </div>
  <label><?= htmlspecialchars(at('payment_password')) ?>
    <input type="password" value="" placeholder="•••••••• hidden" disabled autocomplete="off">
  </label>
</div>
<?php else: ?>
<form method="post" class="admin-card">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
  <h2><?= htmlspecialchars(at('payment_section_login')) ?></h2>
  <p class="muted"><?= htmlspecialchars(at('payment_security_note')) ?></p>
  <div class="grid-2">
    <label><?= htmlspecialchars(at('payment_portal_url')) ?><input type="text" name="billing_portal_url" value="<?= htmlspecialchars($s['billing_portal_url'] ?? '') ?>"></label>
    <label><?= htmlspecialchars(at('payment_service_name')) ?><input type="text" name="billing_service_name" value="<?= htmlspecialchars($s['billing_service_name'] ?? '') ?>"></label>
    <label><?= htmlspecialchars(at('payment_client_name')) ?><input type="text" name="billing_client_name" value="<?= htmlspecialchars($s['billing_client_name'] ?? '') ?>"></label>
    <label><?= htmlspecialchars(at('payment_client_email')) ?><input type="text" name="billing_client_email" value="<?= htmlspecialchars($s['billing_client_email'] ?? '') ?>"></label>
  </div>
  <label><?= htmlspecialchars(at('payment_password')) ?>
    <input type="password" name="billing_client_password" value="" placeholder="<?= !empty($s['billing_client_password']) ? '•••••••• (saved)' : '' ?>" autocomplete="new-password">
  </label>
  <button type="submit" class="btn"><?= htmlspecialchars(at('save')) ?></button>
</form>
<?php endif; ?>

<?php admin_footer(); ?>
