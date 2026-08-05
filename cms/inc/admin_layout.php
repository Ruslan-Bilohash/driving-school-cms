<?php
require_once __DIR__ . '/admin_lang.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/bilohash_client.php';

function unpaid_invoice_count(): int {
    // Portfolio demo: never call external billing with stored credentials
    if (defined('DS_DEMO_MODE') && DS_DEMO_MODE) {
        return 0;
    }
    $s = get_settings();
    $invoices = bilohash_fetch_unpaid_invoices($s['billing_client_email'] ?? '', $s['billing_client_password'] ?? '');
    return is_array($invoices) ? count($invoices) : 0;
}

function ds_demo_mode(): bool {
    return defined('DS_DEMO_MODE') && DS_DEMO_MODE;
}

/** Never echo real secrets in HTML */
function ds_secret_placeholder(string $stored): string {
    return $stored !== '' ? '•••••••• (saved — hidden in demo)' : '';
}

function admin_header(string $title, string $active): void {
    $alang = get_admin_lang();
    $__greeting = admin_greeting(at('greeting_stranger', 'stranger'));
    $__unpaidCount = unpaid_invoice_count();
    ?><!DOCTYPE html>
<html lang="<?= $alang ?>">
<head>
<meta charset="UTF-8">
<script>window.DS_BASE=<?= json_encode(BASE_PATH) ?>;var BASE_PATH=window.DS_BASE;</script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> - Admin</title>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Sora:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset_url('/assets/css/admin.css') ?>">
</head>
<body>
<div class="admin-shell">
  <div class="admin-topbar">
    <span class="brand" style="display:flex;align-items:center;gap:8px"><img src="<?= BASE_PATH ?>/assets/img/logo-v3.webp" alt="" width="26" height="26" style="border-radius:6px" onerror="this.style.display='none'"><?= htmlspecialchars(defined('SITE_NAME') ? SITE_NAME : 'Admin') ?> Admin<?= ds_demo_mode() ? ' · Demo' : '' ?></span>
    <a href="<?= BASE_PATH ?>/admin/orders.php" class="<?= $active === 'orders' ? 'active' : '' ?>"><?= htmlspecialchars(at('nav_orders')) ?></a>
    <a href="<?= BASE_PATH ?>/admin/categories.php" class="<?= $active === 'categories' ? 'active' : '' ?>"><?= htmlspecialchars(at('nav_categories')) ?></a>
    <a href="<?= BASE_PATH ?>/admin/news.php" class="<?= $active === 'news' ? 'active' : '' ?>"><?= htmlspecialchars(at('nav_news')) ?></a>
    <a href="<?= BASE_PATH ?>/admin/gallery.php" class="<?= $active === 'gallery' ? 'active' : '' ?>"><?= htmlspecialchars(at('nav_gallery', 'Gallery')) ?></a>
    <a href="<?= BASE_PATH ?>/admin/settings.php" class="<?= $active === 'settings' ? 'active' : '' ?>"><?= htmlspecialchars(at('nav_settings')) ?></a>
    <a href="<?= BASE_PATH ?>/admin/payment.php" class="<?= $active === 'payment' ? 'active' : '' ?>" style="position:relative"><?= htmlspecialchars(at('nav_payment')) ?><?php if ($__unpaidCount > 0): ?><span class="nav-badge"><?= $__unpaidCount ?></span><?php endif; ?></a>
    <a href="<?= BASE_PATH ?>/admin/activity.php" class="<?= $active === 'activity' ? 'active' : '' ?>"><?= htmlspecialchars(at('nav_activity', 'Activity')) ?></a>
    <a href="<?= BASE_PATH ?>/" target="_blank" rel="noopener"><?= htmlspecialchars(at('nav_site')) ?></a>
    <div class="admin-lang-switch">
      <select onchange="location.href=this.value">
        <?php foreach (admin_lang_options() as $code => $label):
            $qs = $_GET; $qs['alang'] = $code;
        ?>
          <option value="?<?= htmlspecialchars(http_build_query($qs)) ?>" <?= $code === $alang ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <a href="<?= BASE_PATH ?>/admin/logout.php"><?= htmlspecialchars(at('nav_logout')) ?></a>
  </div>
  <main class="admin-main">
    <?php if ($__greeting): ?><div class="admin-greeting"><?= htmlspecialchars($__greeting) ?></div><?php endif; ?>
<?php
}

function admin_footer(): void {
?>
  </main>
  <footer class="admin-footer">
    <span><?= htmlspecialchars(at('footer_text')) ?> â€” &copy; <?= date('Y') ?></span>
    <a class="admin-footer-link" href="<?= BASE_PATH ?>/admin/activity.php"><?= icon('clock') ?> <?= htmlspecialchars(at('nav_activity', 'Activity')) ?></a>
    <a class="admin-footer-link" href="https://bilohash.com/client/" target="_blank" rel="noopener"><?= icon('code') ?> bilohash.com â€” <?= htmlspecialchars(at('footer_ecosystem')) ?></a>
  </footer>
</div>
<script src="<?= asset_url('/assets/js/admin.js') ?>" defer></script>
</body>
</html>
<?php
}

function rich_text_editor(string $name, string $value): void {
    ?>
    <div class="rte">
      <div class="rte-toolbar">
        <button type="button" class="rte-btn" data-cmd="bold" title="Bold"><b>B</b></button>
        <button type="button" class="rte-btn" data-cmd="italic" title="Italic"><i>I</i></button>
        <button type="button" class="rte-btn" data-cmd="underline" title="Underline"><u>U</u></button>
        <span class="rte-sep"></span>
        <button type="button" class="rte-btn" data-cmd="formatBlock" data-value="H2" title="Heading">H2</button>
        <button type="button" class="rte-btn" data-cmd="formatBlock" data-value="H3" title="Subheading">H3</button>
        <button type="button" class="rte-btn" data-cmd="formatBlock" data-value="P" title="Paragraph">Â¶</button>
        <span class="rte-sep"></span>
        <button type="button" class="rte-btn" data-cmd="insertUnorderedList" title="Bullet list">â€¢ List</button>
        <button type="button" class="rte-btn" data-cmd="insertOrderedList" title="Numbered list">1. List</button>
        <span class="rte-sep"></span>
        <button type="button" class="rte-btn" data-cmd="createLink" title="Insert link">ðŸ”— Link</button>
        <button type="button" class="rte-btn" data-cmd="unlink" title="Remove link">ðŸ”—âœ•</button>
        <span class="rte-sep"></span>
        <button type="button" class="rte-btn" data-cmd="undo" title="Undo">â†º</button>
        <button type="button" class="rte-btn" data-cmd="redo" title="Redo">â†»</button>
      </div>
      <div class="rte-editor" contenteditable="true"><?= $value ?: '<p></p>' ?></div>
      <textarea name="<?= htmlspecialchars($name) ?>" class="rte-source" hidden><?= htmlspecialchars($value) ?></textarea>
    </div>
    <?php
}

function image_uploader(string $name, string $currentUrl, string $csrf): void {
    ?>
    <div class="image-uploader" data-csrf="<?= htmlspecialchars($csrf) ?>">
      <div class="image-drop" tabindex="0">
        <img class="image-preview" src="<?= htmlspecialchars($currentUrl) ?>" alt="">
        <span class="image-drop-hint"><?= htmlspecialchars(at('image_hint', 'Drag an image here or click to browse (JPG/PNG/WebP, auto-converted to WebP)')) ?></span>
      </div>
      <input type="file" accept="image/*" class="image-file-input" hidden>
      <input type="hidden" name="<?= htmlspecialchars($name) ?>" class="image-value" value="<?= htmlspecialchars($currentUrl) ?>">
    </div>
    <?php
}
