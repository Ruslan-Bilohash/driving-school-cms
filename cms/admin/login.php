<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/admin_lang.php';

admin_session_start();
$alang = get_admin_lang();

if (is_logged_in()) {
    header('Location: ' . BASE_PATH . '/admin/orders.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    if (attempt_login($username, $password)) {
        header('Location: ' . BASE_PATH . '/admin/orders.php');
        exit;
    }
    $error = at('login_error');
}

$isDemo = defined('DS_DEMO_MODE') && DS_DEMO_MODE;
$demoUser = $isDemo ? 'demo' : '';
$demoPass = $isDemo ? 'demo' : '';
?><!DOCTYPE html>
<html lang="<?= htmlspecialchars($alang) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars(at('login_title')) ?> — <?= htmlspecialchars(SITE_NAME) ?></title>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Sora:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset_url('/assets/css/admin.css') ?>">
<style>
.login-demo-hint{margin:0 0 14px;padding:10px 12px;border-radius:10px;background:#eff6ff;border:1px solid #bfdbfe;color:#1e3a8a;font-size:.88rem;line-height:1.4}
.login-demo-hint strong{font-family:ui-monospace,monospace}
.login-card input[readonly]{background:#f8fafc}
</style>
</head>
<body>
<div class="login-wrap">
  <form class="login-card" method="post" autocomplete="on" novalidate>
    <div style="text-align:center;margin-bottom:14px">
      <select onchange="location.href='?alang='+this.value" style="background:#f4f6fb;color:#121826;border:1px solid #e3e7f0;border-radius:8px;padding:6px 10px;font:inherit;font-size:.85rem">
        <?php foreach (admin_lang_options() as $code => $label): ?>
          <option value="<?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>" <?= $code === $alang ? 'selected' : '' ?> style="color:#121826"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <img src="<?= BASE_PATH ?>/assets/img/logo-v3.webp" alt="" width="56" height="56" style="display:block;margin:0 auto 14px;border-radius:14px" onerror="this.style.display='none'">
    <h1><?= htmlspecialchars(at('login_title')) ?></h1>
    <?php if ($isDemo): ?>
    <div class="login-demo-hint">
      <strong>BILOHASH demo</strong> — credentials are pre-filled. Click Login to open the admin panel.<br>
      User: <strong>demo</strong> · Password: <strong>demo</strong>
    </div>
    <?php endif; ?>
    <?php if ($error): ?><div class="alert alert-err"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <label><?= htmlspecialchars(at('login_username')) ?>
      <input type="text" name="username" value="<?= htmlspecialchars($demoUser) ?>" autocomplete="username" required autofocus>
    </label>
    <label><?= htmlspecialchars(at('login_password')) ?>
      <input type="password" name="password" value="<?= htmlspecialchars($demoPass) ?>" autocomplete="current-password" required>
    </label>
    <button type="submit" class="btn"><?= htmlspecialchars(at('login_submit')) ?></button>
  </form>
</div>
</body>
</html>
