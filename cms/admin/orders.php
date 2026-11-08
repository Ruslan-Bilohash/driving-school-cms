<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/store.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/activity_log.php';
require_once __DIR__ . '/../inc/marketing.php';
require_once __DIR__ . '/../inc/admin_layout.php';

require_login();

$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        $notice = 'err:' . at('session_expired');
    } else {
        $orders = read_json('orders.json', []);
        $id = (int)($_POST['id'] ?? 0);
        if (($_POST['action'] ?? '') === 'update_status') {
            foreach ($orders as &$o) {
                if ($o['id'] === $id) { $o['status'] = $_POST['status'] ?? 'new'; break; }
            }
            unset($o);
            write_json('orders.json', $orders);
            log_activity('order_status', "id={$id} -> " . ($_POST['status'] ?? ''));
            $notice = 'ok:' . at('orders_status_updated');
        } elseif (($_POST['action'] ?? '') === 'delete') {
            $orders = array_values(array_filter($orders, fn($o) => $o['id'] !== $id));
            write_json('orders.json', $orders);
            log_activity('order_delete', "id={$id}");
            $notice = 'ok:' . at('orders_deleted');
        }
    }
}

$orders = read_json('orders.json', []);
usort($orders, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));

$statusLabels = ['new' => at('orders_status_new'), 'contacted' => at('orders_status_contacted'), 'done' => at('orders_status_done')];
$statusBadge = ['new' => 'badge-new', 'contacted' => 'badge-contacted', 'done' => 'badge-done'];

admin_header(at('orders_title'), 'orders');
?>
<h1><?= htmlspecialchars(at('orders_title')) ?> <?php if (!empty($orders)): ?><span class="muted">(<?= count($orders) ?>)</span><?php endif; ?></h1>
<?php if ($notice): [$type, $text] = explode(':', $notice, 2); ?>
  <div class="alert alert-<?= $type === 'ok' ? 'ok' : 'err' ?>"><?= htmlspecialchars($text) ?></div>
<?php endif; ?>

<?php if (empty($orders)): ?>
  <div class="admin-card"><p class="empty"><?= htmlspecialchars(at('orders_empty')) ?></p></div>
<?php else: ?>
  <div class="order-list">
    <?php foreach ($orders as $o): ?>
    <div class="order-card">
      <div class="order-card-top">
        <div>
          <strong><?= htmlspecialchars($o['name'] ?? '') ?></strong>
          <span class="badge <?= $statusBadge[$o['status'] ?? 'new'] ?? 'badge-new' ?>"><?= htmlspecialchars($statusLabels[$o['status'] ?? 'new'] ?? $statusLabels['new']) ?></span>
        </div>
        <span class="muted"><?= htmlspecialchars($o['created_at'] ?? '') ?></span>
      </div>

      <div class="order-card-meta">
        <a href="tel:<?= htmlspecialchars($o['phone'] ?? '') ?>"><?= icon('phone') ?> <?= htmlspecialchars($o['phone'] ?? '') ?></a>
        <?php if (!empty($o['email'])): ?>
          <a href="mailto:<?= htmlspecialchars($o['email']) ?>"><?= icon('mail') ?> <?= htmlspecialchars($o['email']) ?></a>
        <?php endif; ?>
        <span class="chip"><?= htmlspecialchars($o['category'] ?? '') ?></span>
        <span class="chip chip-muted"><?= strtoupper(htmlspecialchars($o['lang'] ?? '')) ?></span>
        <?php if (!empty($o['source_page'])): ?>
          <span class="chip chip-muted" title="<?= htmlspecialchars(at('orders_source_page', 'Source page')) ?>">📍 <?= htmlspecialchars($o['source_page']) ?></span>
        <?php endif; ?>
        <?php if (isset($o['marketing']) && is_array($o['marketing'])): $mkChannel = marketing_channel($o); ?>
          <a class="chip chip-mk" href="<?= BASE_PATH ?>/admin/marketing.php" title="<?= htmlspecialchars(at('mk_col_source')) ?>">📣 <?= htmlspecialchars(at('mk_channel_' . $mkChannel)) ?> · <?= htmlspecialchars(marketing_source_label($o)) ?><?= !empty($o['marketing']['utm_campaign']) ? ' · ' . htmlspecialchars($o['marketing']['utm_campaign']) : '' ?></a>
        <?php endif; ?>
        <span class="chip chip-muted" title="Email"><?= !empty($o['email_sent']) ? '✅' : '⚠️' ?> email</span>
        <span class="chip chip-muted" title="Telegram"><?= !empty($o['telegram_sent']) ? '✅' : '⚠️' ?> tg</span>
      </div>

      <?php if (!empty($o['message'])): ?>
        <p class="order-card-message"><?= nl2br(htmlspecialchars($o['message'])) ?></p>
      <?php endif; ?>

      <div class="order-card-actions">
        <form method="post" class="status-form">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
          <input type="hidden" name="action" value="update_status">
          <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
          <select name="status" onchange="this.form.submit()">
            <?php foreach ($statusLabels as $val => $label): ?>
              <option value="<?= $val ?>" <?= ($o['status'] ?? 'new') === $val ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
          </select>
        </form>
        <form method="post" onsubmit="return confirm('<?= htmlspecialchars(at('orders_delete_confirm')) ?>')">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
          <button type="submit" class="btn btn-danger btn-sm"><?= htmlspecialchars(at('delete')) ?></button>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php admin_footer(); ?>
