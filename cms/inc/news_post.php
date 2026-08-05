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
$slug = (string)($_GET['slug'] ?? '');
$post = get_news_by_slug($lang, $slug);
$isAdmin = is_logged_in();

if (!$post || (empty($post['published']) && !$isAdmin)) {
    http_response_code(404);
    $post = null;
}
?><!DOCTYPE html>
<html lang="<?= $t['html_lang'] ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $post ? htmlspecialchars($post['title']) . ' â€” ' . htmlspecialchars(SITE_NAME) : htmlspecialchars($t['404_title']) ?></title>
<?php if ($post): ?>
<meta name="description" content="<?= htmlspecialchars($post['meta_description'] ?: $post['excerpt']) ?>">
<meta property="og:title" content="<?= htmlspecialchars($post['title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($post['meta_description'] ?: $post['excerpt']) ?>">
<meta property="og:type" content="article">
<meta property="og:image" content="<?= htmlspecialchars(!empty($post['image']) ? SITE_URL . $post['image'] : SITE_URL . '/assets/img/og-image.png') ?>">
<link rel="canonical" href="<?= SITE_URL . $prefix ?>/news-post.php?slug=<?= urlencode($post['slug']) ?>">
<script type="application/ld+json"><?php echo json_encode([
  "@context" => "https://schema.org",
  "@type" => "NewsArticle",
  "headline" => $post['title'],
  "datePublished" => $post['created_at'],
  "dateModified" => $post['updated_at'] ?? $post['created_at'],
  "author" => ["@type" => "Organization", "name" => SITE_NAME],
  "publisher" => ["@type" => "Organization", "name" => SITE_NAME],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
<?php else: ?>
<meta name="robots" content="noindex">
<?php endif; ?>
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
      <a href="<?= $prefix ?>/news.php" class="btn btn-primary btn-sm"><?= icon('arrow') ?> <?= htmlspecialchars($t['news_page']['back']) ?></a>
      <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="mainNav" aria-label="Menu">
        <?= icon('menu', 'icon icon-open') ?><?= icon('close', 'icon icon-close') ?>
      </button>
    </div>
  </div>
</header>

<main class="container section" style="max-width:760px">
<?php if ($post): ?>
  <?php if ($isAdmin): ?>
    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:14px;flex-wrap:wrap">
      <?php if (empty($post['published'])): ?><span class="eyebrow" style="color:var(--accent-dark)">ðŸ”’ Draft â€” only visible to you</span><?php else: ?><span></span><?php endif; ?>
      <a href="<?= BASE_PATH ?>/admin/news.php?lang=<?= $lang ?>&edit=<?= (int)$post['id'] ?>" class="btn btn-primary btn-sm">âœï¸ Edit this article</a>
    </div>
  <?php endif; ?>
  <p class="muted" style="color:var(--text-muted);font-size:.85rem"><?= htmlspecialchars(substr($post['created_at'], 0, 10)) ?></p>
  <h1><?= htmlspecialchars($post['title']) ?></h1>
  <?php if (!empty($post['image'])): ?>
    <img src="<?= htmlspecialchars($post['image']) ?>" alt="" style="width:100%;border-radius:var(--radius);margin-bottom:24px;max-height:420px;object-fit:cover">
  <?php endif; ?>
  <div class="news-content" style="line-height:1.8;font-size:1.05rem"><?= $post['content'] ?></div>
<?php else: ?>
  <h1><?= htmlspecialchars($t['404_title']) ?></h1>
  <p class="lead"><?= htmlspecialchars($t['404_text']) ?></p>
  <a href="<?= $prefix ?>/news.php" class="btn btn-primary"><?= icon('arrow') ?> <?= htmlspecialchars($t['news_page']['back']) ?></a>
<?php endif; ?>
</main>

<?php render_site_footer($t, $lang, $s, $languages); ?>
<?php render_popups($t, $lang); ?>
<script src="<?= asset_url('/assets/js/main.js') ?>" defer></script>
</body>
</html>
