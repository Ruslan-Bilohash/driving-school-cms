<?php
// expects $lang and $slug set by the entry file
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/languages.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/categories.php';
require_once __DIR__ . '/districts.php';
require_once __DIR__ . '/popups.php';
require_once __DIR__ . '/footer.php';

$t = require __DIR__ . '/lang/' . $lang . '.php';
$languages = site_languages();
$s = get_settings();
$catSlugs = category_slugs();
$catContent = get_category_content($lang);
$district = get_district($lang, $slug);

if (!$district) {
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
}

$canonical = SITE_URL . district_url($languages, $lang, $slug);
$prefix = $t['dir_prefix'];
$otherSlugs = array_values(array_diff(district_slugs(), [$slug]));
?><!DOCTYPE html>
<html lang="<?= $t['html_lang'] ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= htmlspecialchars($district['meta_title']) ?></title>
<meta name="description" content="<?= htmlspecialchars($district['meta_description']) ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php foreach ($languages as $code => $l): ?>
<link rel="alternate" hreflang="<?= $code ?>" href="<?= SITE_URL . district_url($languages, $code, $slug) ?>">
<?php endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= SITE_URL . district_url($languages, 'lt', $slug) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= htmlspecialchars($district['meta_title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($district['meta_description']) ?>">
<meta property="og:image" content="<?= SITE_URL ?>/assets/img/og-image.png">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="<?= BASE_PATH ?>/assets/img/favicon-v3.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset_url('/assets/css/style.css') ?>">

<script type="application/ld+json">
<?php echo json_encode([
  "@context" => "https://schema.org",
  "@type" => "DrivingSchool",
  "name" => SITE_NAME . ' â€” ' . $district['name'],
  "description" => $district['meta_description'],
  "areaServed" => $district['name'] . ', Vilnius',
  "url" => $canonical,
  "telephone" => $s['phone1'],
  "address" => ["@type" => "PostalAddress", "addressLocality" => "Vilnius", "addressCountry" => "LT"],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">
<?php echo json_encode([
  "@context" => "https://schema.org",
  "@type" => "BreadcrumbList",
  "itemListElement" => [
    ["@type" => "ListItem", "position" => 1, "name" => $t['nav']['home'], "item" => SITE_URL . $languages[$lang]['path']],
    ["@type" => "ListItem", "position" => 2, "name" => $district['name'], "item" => $canonical],
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
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
          <li role="none"><a role="menuitem" href="<?= district_url($languages, $code, $slug) ?>" class="<?= $code === $lang ? 'active' : '' ?>"><span class="lang-flag"><?= $l['flag'] ?></span> <span class="lang-code"><?= $l['code'] ?></span> <span class="lang-name"><?= htmlspecialchars($l['label']) ?></span></a></li>
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

<main>
  <div class="container" style="padding-top:28px">
    <nav aria-label="Breadcrumb" style="font-size:.85rem;color:var(--text-muted)">
      <a href="<?= $prefix ?: '/' ?>"><?= htmlspecialchars($t['nav']['home']) ?></a>
      <span> / </span>
      <span><?= htmlspecialchars($district['name']) ?></span>
    </nav>
  </div>

  <section class="section" style="padding-top:24px">
    <div class="container">
      <div class="section-head" style="text-align:left;margin:0 0 32px;max-width:760px">
        <span class="eyebrow"><?= icon('pin') ?> <?= htmlspecialchars($district['name']) ?>, Vilnius</span>
        <h1><?= htmlspecialchars($district['title']) ?></h1>
        <p class="lead"><?= htmlspecialchars($district['intro']) ?></p>
        <p><?= htmlspecialchars($district['why']) ?></p>
        <a href="<?= $prefix ?: '/' ?>#contact" class="btn btn-primary btn-lg" style="margin-top:14px"><?= icon('arrow') ?> <?= htmlspecialchars($t['hero']['cta_primary']) ?></a>
      </div>

      <div class="section-head">
        <span class="eyebrow"><?= htmlspecialchars($t['categories']['eyebrow']) ?></span>
        <h2><?= htmlspecialchars($t['categories']['title']) ?></h2>
      </div>
      <div class="cat-grid">
        <?php foreach ($catSlugs as $i => $cs): if (!isset($catContent[$cs])) continue; ?>
        <a class="cat-card" style="display:block" href="<?= category_url($languages, $lang, $cs) ?>">
          <span class="cat-code"><?= htmlspecialchars($catContent[$cs]['title']) ?></span>
          <h3><?= htmlspecialchars($catContent[$cs]['h1']) ?></h3>
        </a>
        <?php endforeach; ?>
      </div>

      <div class="section-head" style="margin-top:56px">
        <span class="eyebrow"><?= icon('pin') ?></span>
        <h2><?= htmlspecialchars($t['district_page']['other_areas']) ?></h2>
      </div>
      <div class="cat-grid">
        <?php foreach ($otherSlugs as $os): $od = get_district($lang, $os); if (!$od) continue; ?>
        <a class="cat-card" style="display:block" href="<?= district_url($languages, $lang, $os) ?>">
          <h3><?= htmlspecialchars($od['name']) ?></h3>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php render_site_footer($t, $lang, $s, $languages); ?>
<?php render_popups($t, $lang); ?>
<script src="<?= asset_url('/assets/js/main.js') ?>" defer></script>
</body>
</html>
