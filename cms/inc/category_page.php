<?php
// expects $lang and $slug set by the entry file
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/languages.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/categories.php';
require_once __DIR__ . '/popups.php';
require_once __DIR__ . '/footer.php';

$t = require __DIR__ . '/lang/' . $lang . '.php';
$languages = site_languages();
$s = get_settings();
$meta = category_meta();
$cat = get_category($lang, $slug);

if (!$cat || !isset($meta[$slug])) {
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
}

$m = $meta[$slug];
$priceValue = $s['price_full'][$m['price_key']] ?? null;
$canonical = SITE_URL . category_url($languages, $lang, $slug);
$prefix = $t['dir_prefix'];
?><!DOCTYPE html>
<html lang="<?= $t['html_lang'] ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= htmlspecialchars($cat['meta_title']) ?></title>
<meta name="description" content="<?= htmlspecialchars($cat['meta_description']) ?>">
<link rel="canonical" href="<?= $canonical ?>">
<?php foreach ($languages as $code => $l): ?>
<link rel="alternate" hreflang="<?= $code ?>" href="<?= SITE_URL . category_url($languages, $code, $slug) ?>">
<?php endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= SITE_URL . category_url($languages, 'lt', $slug) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= htmlspecialchars($cat['meta_title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($cat['meta_description']) ?>">
<meta property="og:image" content="<?= htmlspecialchars(!empty($cat['image']) ? SITE_URL . $cat['image'] : SITE_URL . '/assets/img/og-image.png') ?>">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="<?= BASE_PATH ?>/assets/img/favicon-v3.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset_url('/assets/css/style.css') ?>">

<script type="application/ld+json">
<?php echo json_encode([
  "@context" => "https://schema.org",
  "@type" => "Course",
  "name" => $cat['h1'],
  "description" => $cat['meta_description'],
  "provider" => ["@type" => "DrivingSchool", "name" => SITE_NAME, "sameAs" => SITE_URL],
  "offers" => $priceValue !== null ? [
    "@type" => "Offer",
    "price" => (float) $priceValue,
    "priceCurrency" => "EUR",
    "url" => $canonical,
  ] : null,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">
<?php echo json_encode([
  "@context" => "https://schema.org",
  "@type" => "FAQPage",
  "mainEntity" => array_map(fn($item) => [
    "@type" => "Question", "name" => $item['q'],
    "acceptedAnswer" => ["@type" => "Answer", "text" => $item['a']],
  ], $cat['faq']),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<script type="application/ld+json">
<?php echo json_encode([
  "@context" => "https://schema.org",
  "@type" => "BreadcrumbList",
  "itemListElement" => [
    ["@type" => "ListItem", "position" => 1, "name" => $t['nav']['home'], "item" => SITE_URL . $languages[$lang]['path']],
    ["@type" => "ListItem", "position" => 2, "name" => $t['nav']['categories'], "item" => SITE_URL . $languages[$lang]['path'] . '#categories'],
    ["@type" => "ListItem", "position" => 3, "name" => $cat['title'], "item" => $canonical],
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
          <li role="none"><a role="menuitem" href="<?= category_url($languages, $code, $slug) ?>" class="<?= $code === $lang ? 'active' : '' ?>"><span class="lang-flag"><?= $l['flag'] ?></span> <span class="lang-code"><?= $l['code'] ?></span> <span class="lang-name"><?= htmlspecialchars($l['label']) ?></span></a></li>
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
      <a href="<?= $prefix ?: '/' ?>#categories"><?= htmlspecialchars($t['nav']['categories']) ?></a>
      <span> / </span>
      <span><?= htmlspecialchars($cat['title']) ?></span>
    </nav>
  </div>

  <section class="section" style="padding-top:24px">
    <div class="container">
      <div class="section-head" style="text-align:left;margin:0 0 32px;max-width:760px">
        <span class="eyebrow"><?= icon($m['icon']) ?> <?= htmlspecialchars($cat['title']) ?></span>
        <h1><?= htmlspecialchars($cat['h1']) ?></h1>
        <p class="lead"><?= htmlspecialchars($cat['intro']) ?></p>
        <p><?= htmlspecialchars($cat['who_for']) ?></p>
      </div>

      <?php if (!empty($cat['image'])): ?>
        <img src="<?= htmlspecialchars($cat['image']) ?>" alt="<?= htmlspecialchars($cat['title']) ?>" style="width:100%;max-height:360px;object-fit:cover;border-radius:var(--radius-lg);margin-bottom:32px">
      <?php endif; ?>

      <div class="feature-grid cat-feature-grid">
        <div class="feature-card">
          <h3><?= icon('check', 'icon') ?> <?= htmlspecialchars($t['cat_page']['requirements']) ?></h3>
          <ul style="margin:0;padding-left:18px;color:var(--text-muted);font-size:.93rem;line-height:1.7">
            <?php foreach ($cat['requirements'] as $r): ?><li><?= htmlspecialchars($r) ?></li><?php endforeach; ?>
          </ul>
        </div>
        <div class="feature-card">
          <h3><?= icon('shield', 'icon') ?> <?= htmlspecialchars($t['cat_page']['includes']) ?></h3>
          <ul style="margin:0;padding-left:18px;color:var(--text-muted);font-size:.93rem;line-height:1.7">
            <?php foreach ($cat['includes'] as $inc): ?><li><?= htmlspecialchars($inc) ?></li><?php endforeach; ?>
          </ul>
        </div>
        <div class="feature-card" style="text-align:center">
          <span class="feature-icon" style="margin-inline:auto"><?= icon('coin') ?></span>
          <?php if ($priceValue !== null): ?>
            <div style="font-family:var(--font-head);font-size:2rem;font-weight:800;color:var(--primary);margin:6px 0"><?= htmlspecialchars((string)$priceValue) ?> â‚¬</div>
          <?php endif; ?>
          <p class="muted" style="color:var(--text-muted);font-size:.85rem"><?= htmlspecialchars($cat['duration']) ?></p>
          <a href="<?= $prefix ?: '/' ?>#contact" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:10px"><?= icon('arrow') ?> <?= htmlspecialchars($t['hero']['cta_primary']) ?></a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-alt">
    <div class="container">
      <div class="section-head">
        <span class="eyebrow"><?= htmlspecialchars($t['faq']['eyebrow']) ?></span>
        <h2><?= htmlspecialchars($t['faq']['title']) ?></h2>
      </div>
      <div class="faq-list">
        <?php foreach ($cat['faq'] as $item): ?>
        <div class="faq-item">
          <button class="faq-q" aria-expanded="false"><span><?= htmlspecialchars($item['q']) ?></span><?= icon('chevron', 'icon faq-chevron') ?></button>
          <div class="faq-a"><p><?= htmlspecialchars($item['a']) ?></p></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section section-alt">
    <div class="container">
      <div class="section-head">
        <span class="eyebrow"><?= htmlspecialchars($t['about']['eyebrow']) ?></span>
        <h2><?= htmlspecialchars($t['about']['title']) ?></h2>
        <p class="lead"><?= htmlspecialchars($t['about']['text']) ?></p>
      </div>
      <div class="feature-grid">
        <?php foreach ($t['about']['features'] as $f): ?>
        <div class="feature-card">
          <span class="feature-icon"><?= icon($f['icon']) ?></span>
          <h3><?= htmlspecialchars($f['title']) ?></h3>
          <p><?= htmlspecialchars($f['text']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php
  $allOtherSlugs = array_values(array_diff(category_slugs(), [$slug]));
  $linkSlugs = array_values(array_unique(array_merge($cat['related'] ?? [], $allOtherSlugs)));
  ?>
  <?php if (!empty($linkSlugs)): ?>
  <section class="section">
    <div class="container">
      <div class="section-head">
        <span class="eyebrow"><?= htmlspecialchars($t['categories']['eyebrow']) ?></span>
        <h2><?= htmlspecialchars($t['categories']['title']) ?></h2>
        <p class="lead"><?= htmlspecialchars($t['categories']['subtitle']) ?></p>
      </div>
      <div class="cat-grid">
        <?php foreach ($linkSlugs as $relSlug):
          $relCat = get_category($lang, $relSlug);
          $relMeta = $meta[$relSlug] ?? null;
          if (!$relCat || !$relMeta) continue;
        ?>
        <a class="cat-card" href="<?= category_url($languages, $lang, $relSlug) ?>" style="display:block">
          <span class="cat-icon"><?= icon($relMeta['icon']) ?></span>
          <h3><?= htmlspecialchars($relCat['title']) ?></h3>
          <p><?= htmlspecialchars($relCat['intro']) ?></p>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>
</main>

<?php render_site_footer($t, $lang, $s, $languages); ?>

<?php render_popups($t, $lang); ?>
<script src="<?= asset_url('/assets/js/main.js') ?>" defer></script>
</body>
</html>
