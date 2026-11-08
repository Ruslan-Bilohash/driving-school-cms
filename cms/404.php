<?php
http_response_code(404);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/icons.php';
$uri = $_SERVER['REQUEST_URI'] ?? '';
$lang = 'no';
foreach (['uk', 'en', 'ru'] as $code) {
    if (strpos($uri, '/' . $code . '/') === 0) { $lang = $code; break; }
}
$t = require __DIR__ . '/inc/lang/' . $lang . '.php';
$home = $lang === 'lt' ? '/' : '/' . $lang . '/';
?><!DOCTYPE html>
<html lang="<?= $t['html_lang'] ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($t['404_title']) ?> â€” <?= htmlspecialchars(SITE_NAME) ?></title>
<meta name="robots" content="noindex, follow">
<link rel="icon" href="<?= BASE_PATH ?>/assets/img/favicon-v3.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Sora:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset_url('/assets/css/style.css') ?>">
</head>
<body>
<main class="container center" style="min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px">
  <span class="logo-mark" style="width:64px;height:64px"><img src="<?= BASE_PATH ?>/assets/img/logo-v3.webp" alt="" width="64" height="64"></span>
  <h1>404</h1>
  <h3><?= htmlspecialchars($t['404_title']) ?></h3>
  <p class="lead"><?= htmlspecialchars($t['404_text']) ?></p>
  <a href="<?= $home ?>" class="btn btn-primary btn-lg"><?= icon('arrow') ?> <?= htmlspecialchars($t['404_cta']) ?></a>
</main>
</body>
</html>
