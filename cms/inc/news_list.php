<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/languages.php';
require_once __DIR__ . '/news_store.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/footer.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/popups.php';

$t = require __DIR__ . '/lang/' . $lang . '.php';
$languages = site_languages();
$prefix = $t['dir_prefix'];
$s = get_settings();
$isAdmin = is_logged_in();
$posts = $isAdmin
    ? (function() use ($lang) {
        $all = get_all_news($lang);
        usort($all, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
        return $all;
    })()
    : get_published_news($lang);
?><!DOCTYPE html>
<html lang="<?= $t['html_lang'] ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($t['news_page']['title']) ?> â€” <?= htmlspecialchars(SITE_NAME) ?></title>
<meta name="description" content="<?= htmlspecialchars($t['news_page']['meta_description']) ?>">
<link rel="canonical" href="<?= SITE_URL . $prefix ?>/news.php">
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
      <a href="<?= $prefix ?>/news.php" class="active"><?= htmlspecialchars($t['nav']['news']) ?></a>
      <a href="<?= $prefix ?>/gallery.php"><?= htmlspecialchars($t['nav']['gallery']) ?></a>
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
          <li role="none"><a role="menuitem" href="<?= $l['path'] ?>news.php" class="<?= $code === $lang ? 'active' : '' ?>"><span class="lang-flag"><?= $l['flag'] ?></span> <span class="lang-code"><?= $l['code'] ?></span> <span class="lang-name"><?= htmlspecialchars($l['label']) ?></span></a></li>
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
      <span class="eyebrow"><?= htmlspecialchars($t['nav']['news']) ?></span>
      <h1><?= htmlspecialchars($t['news_page']['title']) ?></h1>
    </div>

    <?php if (empty($posts)): ?>
      <p class="empty" style="text-align:center;color:var(--text-muted)"><?= htmlspecialchars($t['news_page']['empty']) ?></p>
    <?php else: ?>
      <div class="feature-grid" style="grid-template-columns:repeat(3,1fr)">
        <?php foreach ($posts as $p): ?>
        <div class="feature-card" style="position:relative;padding-top:0;overflow:hidden">
          <?php if ($isAdmin && empty($p['published'])): ?>
            <span class="badge" style="position:absolute;top:12px;right:12px;background:var(--accent);color:#fff;z-index:2">ðŸ”’ Draft</span>
          <?php endif; ?>
          <a style="display:block;color:inherit" href="<?= $prefix ?>/news-post.php?slug=<?= urlencode($p['slug']) ?>">
            <?php if (!empty($p['image'])): ?>
              <img src="<?= htmlspecialchars($p['image']) ?>" alt="" style="width:calc(100% + 48px);margin:0 -24px 16px;max-height:180px;object-fit:cover">
            <?php endif; ?>
            <h3><?= htmlspecialchars($p['title']) ?></h3>
            <p><?= htmlspecialchars($p['excerpt'] ?? '') ?></p>
            <p class="muted" style="color:var(--text-muted);font-size:.82rem;margin-top:10px"><?= htmlspecialchars(substr($p['created_at'] ?? '', 0, 10)) ?></p>
          </a>
          <?php if ($isAdmin): ?>
            <a href="<?= BASE_PATH ?>/admin/news.php?lang=<?= $lang ?>&edit=<?= (int)$p['id'] ?>" class="btn btn-outline btn-sm" style="margin-top:10px">âœï¸ Edit</a>
          <?php endif; ?>
        </div>
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
