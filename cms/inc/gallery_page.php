<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/languages.php';
require_once __DIR__ . '/gallery_store.php';
require_once __DIR__ . '/footer.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/popups.php';

$t = require __DIR__ . '/lang/' . $lang . '.php';
$languages = site_languages();
$prefix = $t['dir_prefix'];
$s = get_settings();
$items = get_gallery_items();
usort($items, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
?><!DOCTYPE html>
<html lang="<?= $t['html_lang'] ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($t['gallery_page']['title']) ?> â€” <?= htmlspecialchars(SITE_NAME) ?></title>
<meta name="description" content="<?= htmlspecialchars($t['gallery_page']['meta_description']) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= htmlspecialchars($t['gallery_page']['title']) ?> â€” <?= htmlspecialchars(SITE_NAME) ?>">
<meta property="og:description" content="<?= htmlspecialchars($t['gallery_page']['meta_description']) ?>">
<meta property="og:image" content="<?= SITE_URL ?>/assets/img/og-image.png">
<link rel="canonical" href="<?= SITE_URL . $prefix ?>/gallery.php">
<link rel="icon" href="<?= BASE_PATH ?>/assets/img/favicon-v3.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset_url('/assets/css/style.css') ?>">
</head>
<body>
<div class="bg-fx" aria-hidden="true"><div class="blob blob-1"></div><div class="blob blob-2"></div></div>

<header class="site-header scrolled" id="siteHeader">
  <div class="container header-inner">
    <a href="<?= $prefix ?: '/' ?>" class="logo">
      <span class="logo-mark"><img src="<?= BASE_PATH ?>/assets/img/logo-v3.webp" alt="" width="38" height="38"></span>
      <span class="logo-text"><?= htmlspecialchars(SITE_NAME) ?></span>
    </a>
    <nav class="main-nav" id="mainNav" aria-label="Main">
      <a href="<?= $prefix ?: '/' ?>#about"><?= htmlspecialchars($t['nav']['about']) ?></a>
      <a href="<?= $prefix ?: '/' ?>#categories"><?= htmlspecialchars($t['nav']['categories']) ?></a>
      <a href="<?= $prefix ?: '/' ?>#prices"><?= htmlspecialchars($t['nav']['prices']) ?></a>
      <a href="<?= $prefix ?: '/' ?>#faq"><?= htmlspecialchars($t['nav']['faq']) ?></a>
      <a href="<?= $prefix ?>/news.php"><?= htmlspecialchars($t['nav']['news']) ?></a>
      <a href="<?= $prefix ?>/gallery.php" class="active"><?= htmlspecialchars($t['nav']['gallery']) ?></a>
      <a href="<?= $prefix ?: '/' ?>#contact"><?= htmlspecialchars($t['nav']['contact']) ?></a>
    </nav>
    <div class="header-actions">
      <div class="lang-dropdown" id="langDropdown">
        <button class="lang-trigger" id="langTrigger" aria-haspopup="true" aria-expanded="false">
          <span><?= $languages[$lang]['flag'] ?> <?= $languages[$lang]['code'] ?></span>
          <?= icon('chevron', 'icon lang-chevron') ?>
        </button>
        <ul class="lang-menu" role="menu">
          <?php foreach ($languages as $code => $l): ?>
          <li role="none"><a role="menuitem" href="<?= $l['path'] ?>gallery.php" class="<?= $code === $lang ? 'active' : '' ?>"><span class="lang-flag"><?= $l['flag'] ?></span> <span class="lang-code"><?= $l['code'] ?></span> <span class="lang-name"><?= htmlspecialchars($l['label']) ?></span></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <a href="<?= $prefix ?: '/' ?>#contact" class="btn btn-primary btn-sm"><?= htmlspecialchars($t['nav']['cta']) ?></a>
      <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="mainNav" aria-label="Menu">
        <?= icon('menu', 'icon icon-open') ?><?= icon('close', 'icon icon-close') ?>
      </button>
    </div>
  </div>
</header>

<main class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow"><?= htmlspecialchars($t['nav']['gallery']) ?></span>
      <h1><?= htmlspecialchars($t['gallery_page']['title']) ?></h1>
    </div>

    <?php if (empty($items)): ?>
      <p class="empty" style="text-align:center;color:var(--text-muted)"><?= htmlspecialchars($t['gallery_page']['empty']) ?></p>
    <?php else: ?>
      <div class="gallery-grid">
        <?php foreach ($items as $it): ?>
        <figure class="gallery-item reveal">
          <img src="<?= htmlspecialchars($it['image']) ?>" alt="<?= htmlspecialchars($it['caption'] ?? '') ?>" loading="lazy">
          <?php if (!empty($it['caption'])): ?>
            <figcaption><?= htmlspecialchars($it['caption']) ?></figcaption>
          <?php endif; ?>
        </figure>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php render_site_footer($t, $lang, $s, $languages); ?>
<?php render_popups($t, $lang); ?>
<script src="<?= asset_url('/assets/js/main.js') ?>" defer></script>
</body>
</html>
