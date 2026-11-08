<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/store.php';
require_once __DIR__ . '/../inc/activity_log.php';
require_once __DIR__ . '/../inc/admin_layout.php';

require_login();

$log = get_activity_log();

$actionLabels = [
    'login' => '🔑 Login',
    'category_save' => '📄 Category saved',
    'news_save' => '📰 Article saved',
    'news_delete' => '🗑 Article deleted',
    'orders_status' => '📋 Order status',
    'order_status' => '📋 Order status changed',
    'order_delete' => '🗑 Order deleted',
    'settings_save' => '⚙️ Settings saved',
    'password_change' => '🔒 Password changed',
    'payment_save' => '💳 Billing info saved',
    'marketing_save' => '📣 Marketing settings saved',
    'marketing_export' => '⬇ Leads exported (CSV)',
];

admin_header(at('activity_title', 'Activity log'), 'activity');
?>
<h1><?= htmlspecialchars(at('activity_title', 'Activity log')) ?></h1>

<div class="admin-card">
  <?php if (empty($log)): ?>
    <p class="empty"><?= htmlspecialchars(at('activity_empty', 'No activity recorded yet.')) ?></p>
  <?php else: ?>
    <table>
      <thead><tr><th><?= htmlspecialchars(at('payment_col_date')) ?></th><th><?= htmlspecialchars(at('settings_username')) ?></th><th>IP</th><th><?= htmlspecialchars(at('activity_action', 'Action')) ?></th><th><?= htmlspecialchars(at('activity_details', 'Details')) ?></th></tr></thead>
      <tbody>
        <?php foreach (array_slice($log, 0, 200) as $entry): ?>
        <tr>
          <td data-label="Time"><?= htmlspecialchars($entry['time'] ?? '') ?></td>
          <td data-label="User"><?= htmlspecialchars($entry['user'] ?? '') ?></td>
          <td data-label="IP"><?= htmlspecialchars($entry['ip'] ?? '') ?></td>
          <td data-label="Action"><?= htmlspecialchars($actionLabels[$entry['action'] ?? ''] ?? ($entry['action'] ?? '')) ?></td>
          <td data-label="Details"><?= htmlspecialchars($entry['details'] ?? '') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php admin_footer(); ?>
